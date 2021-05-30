import sqlite3

def create_connection():
    conn = None
    try:
        conn = sqlite3.connect('Farmaci.db')
        print('Connesso con successo')
    except Error as e:
        print(e)

    return conn

def selectByid(id):
    conn = create_connection();
    cur = conn.cursor()
    cur.execute("SELECT * FROM tabella_utenti WHERE id=?", (id,))

    rows = cur.fetchall()

    for row in rows:
        print(row)

selectByid(1);