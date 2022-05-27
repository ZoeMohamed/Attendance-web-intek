# import the necessary packages
from imutils.video import VideoStream
from imutils.video import FPS
import face_recognition
import argparse
import imutils
import pickle
import time
import cv2
import datetime
import psycopg2
import requests
import threading
import asyncio


async def requestDoor():
	try:
		response = requests.request("GET", "http://192.168.1.86/openTheDoor", headers={}, data = {})
		print("Door open")
	except:
		print("Request DoorOpen Failed ")
	await asyncio.sleep(1)
	print('... Wait')

# construct the argument parser and parse the arguments
# ap = argparse.ArgumentParser()
# ap.add_argument("-c", "--cascade", required=True,
#                 help="path to where the face cascade resides")
# ap.add_argument("-e", "--encodings", required=True,
#                 help="path to serialized db of facial encodings")
# args = vars(ap.parse_args())

# load the known faces and embeddings along with OpenCV's Haar
# cascade for face detection
print("[INFO] initial sql encodings + face detector...")
connection = psycopg2.connect(
	"dbname='la_att' host='localhost' user='postgres' password='postgres'")
connection.autocommit = True
db = connection.cursor()

detector = cv2.CascadeClassifier("haarcascade_frontalface_default.xml")

# initialize the video stream and allow the camera sensor to warm up
print("[INFO] starting video stream...")
vs = VideoStream(src=0).start()
# vs = VideoStream(usePiCamera=True).start()
time.sleep(2.0)

# start the FPS counter
fps = FPS().start()
# loop over frames from the video file stream
while True:
	# grab the frame from the threaded video stream and resize it
	# to 500px (to speedup processing)
	frame = vs.read()
	try:
		frame = imutils.resize(frame, width=320)
		# convert the input frame from (1) BGR to grayscale (for face
		# detection) and (2) from BGR to RGB (for face recognition)
		gray = cv2.cvtColor(frame, cv2.COLOR_BGR2GRAY)
		rgb = cv2.cvtColor(frame, cv2.COLOR_BGR2RGB)

		# detect faces in the grayscale frame
		rects = detector.detectMultiScale(gray, scaleFactor=1.2,
									minNeighbors=7, minSize=(20, 20))

		# OpenCV returns bounding box coordinates in (x, y, w, h) order
		# but we need them in (top, right, bottom, left) order, so we
		# need to do a bit of reordering
		boxes = [(y, x + w, y + h, x) for (x, y, w, h) in rects]
		names = []
		if fps._numFrames % 10 == 0 :

			# compute the facial embeddings for each face bounding box
			encodings = face_recognition.face_encodings(rgb, boxes)


			# loop over the facial embeddings
			for encoding in encodings:
				# attempt to match each face in the input image to our known
				# encodings

				name = "Unknown"
				
				query = "SELECT name,mprof_id FROM vectors WHERE sqrt(power(CUBE(array[{}]) <-> vector_low, 2) + power(CUBE(array[{}]) <-> vector_high, 2)) <= {} ".format(
					','.join(str(s) for s in encoding[0:64]),
					','.join(str(s) for s in encoding[64:128]),
					0.4,    
				) + \
					"ORDER BY sqrt((CUBE(array[{}]) <-> vector_low) + (CUBE(array[{}]) <-> vector_high)) ASC LIMIT 1".format(
					','.join(str(s) for s in encoding[0:64]),
					','.join(str(s) for s in encoding[64:128]),
				)
				db.execute(query)
				row = db.fetchone()
				#print(str(row))
				
				
				if row is not None:
					name = row[0]
					# print(boxes)
					try:
						query_insert = "INSERT INTO m_loggings (mprof_id, name, log_time, sunrise) VALUES ('{}', '{}', '{}', '{}')".format(
							row[1],
							name,
							datetime.datetime.now(),
							datetime.datetime.now(),
						)
						db.execute(query_insert)
						#print("Done Insert "+ str(name))
					except:
						print("Failed Insert")					
					print(str(name) + " at "+str(datetime.datetime.now()))
					# t = threading.Thread(target= requestDoor)
					# t.start()
					
					asyncio.run(requestDoor())
				# update the list of names
				names.append(name)

			# loop over the recognized faces
			for ((top, right, bottom, left), name) in zip(boxes, names):
				# draw the predicted face name on the image
				cv2.rectangle(frame, (left, top), (right, bottom),
							(0, 255, 0), 2)
				y = top - 15 if top - 15 > 15 else top + 15
				cv2.putText(frame, name, (left, y), cv2.FONT_HERSHEY_SIMPLEX,
							0.75, (0, 255, 0), 2)
		else:
			name = ""
			names.append(name)
			for ((top, right, bottom, left), name) in zip(boxes, names):
				# draw the predicted face name on the image
				cv2.rectangle(frame, (left, top), (right, bottom),
								(0, 255, 0), 2)

		# display the image to our screen
		cv2.imshow("Frame", frame)
	except AttributeError:
		print("frame not found")
	key = cv2.waitKey(1) & 0xFF

	# if the `q` key was pressed, break from the loop
	if key == ord("q"):
		break

	# update the FPS counter
	fps.update()


# stop the timer and display FPS information
fps.stop()
print("[INFO] elasped time: {:.2f}".format(fps.elapsed()))
print("[INFO] approx. FPS: {:.2f}".format(fps.fps()))

# do a bit of cleanup
cv2.destroyAllWindows()
vs.stop()
