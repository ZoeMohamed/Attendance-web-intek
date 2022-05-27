# !/usr/bin/env python3
# -*- coding: utf-8 -*-
#
# _encoding.py
# facerecog
#
# Created by Purwo Widodo on 18/12/2019.
# Copyright © 2019 Purwo Widodo. All rights reserved.
#

from imutils import paths
import face_recognition
import pickle
import cv2
import os
import psycopg2
from datetime import datetime
from pathlib import Path
connection = psycopg2.connect("dbname='la_att' host='localhost' user='postgres' password='postgres'")
db = connection.cursor()

# Grabbing
print("[INFO] quantifying faces..")
imagePaths2 = list(paths.list_images("dataset"))
# print(imagePaths)

for (z, imagePath2) in enumerate(imagePaths2):
    # Extract person name
    print("[INFO] processing image {}/{}".format(z + 1, len(imagePaths2)))
    name = imagePath2.split(os.path.sep)[-2]
    # print(name)
    nameFix = Path(imagePath2).stem
    # Load the image and convert it frim RGM (OpenCV ordering) to dlib ordering (RGB)
    image = cv2.imread(imagePath2)
    rgb = cv2.cvtColor(image, cv2.COLOR_BGR2RGB)

    # Detect the coordinates of face
    box = face_recognition.face_locations(rgb, model='hog')

    # Now as we have face coordinates now compute the encodings
    encodings = face_recognition.face_encodings(rgb, box)
    query_check = "SELECT * FROM m_profs WHERE name = %s"

    go_check = db.execute(query_check, (name.title(),))
    found = db.fetchone()
    
    # Loop over the encodings :
    for encoding in encodings:
        # Add the values and names in the empty list
        if len(encodings) > 0:
            print(encodings)
            #print(found)
            
            if found is None: 
                query_sec = "INSERT INTO m_profs (name, position) VALUES ('{}', '{}') RETURNING id".format(
                    name.title(),
                    0,
                )
                db.execute(query_sec)
                idprofs = db.fetchone()
                print("Insert M_profs")
                query = "INSERT INTO vectors (file, vector_low, vector_high, name, mprof_id) VALUES ('{}', Cube(array[{}]), Cube(array[{}]), '{}', '{}')".format(
                    imagePath2,
                    ','.join(str(s) for s in encoding[0:64]),
                    ','.join(str(s) for s in encoding[64:128]),
                    nameFix.title(),
                    idprofs[0],
                )
                
                db.execute(query)
            else:
                query = "INSERT INTO vectors (file, vector_low, vector_high, name, mprof_id) VALUES ('{}', Cube(array[{}]), Cube(array[{}]), '{}', '{}')".format(
                    imagePath2,
                    ','.join(str(s) for s in encoding[0:64]),
                    ','.join(str(s) for s in encoding[64:128]),
                    nameFix.title(),
                    found[0],
                )
                db.execute(query)
        connection.commit()
