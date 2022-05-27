import requests
import datetime
import psycopg2


connection = psycopg2.connect(
        "dbname='la_att' host='localhost' user='postgres' password='postgres'")
connection.autocommit = True
db = connection.cursor()


query = "SELECT id, mprof_id, name, log_time FROM m_loggings WHERE status=0 ORDER BY id ASC"
db.execute(query)
rows = db.fetchall()


for row in rows:
    #print(row[0])
    try:
        url = "https://la-att.intek.co.id/api/recive/data"
        #url = "http://192.168.1.16:8877/api/recive/data"
        payload = {'id': row[1],
        'name': row[2],
        'date_time': row[3],
        'machine_id': '1'}
        headers= {}

        response = requests.request("POST", url, headers=headers, data = payload)
        if response.status_code == 200:
           sql = "UPDATE m_loggings SET status = 1 WHERE id = "+str(row[0])+""
           db.execute(sql)
           print("Done  Push")
        print(response.text.encode('utf8'))
    except Exception as e:
        print("Failed Push Data"+ str(e))