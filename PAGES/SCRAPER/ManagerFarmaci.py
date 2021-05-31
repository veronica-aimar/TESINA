import sqlite3


def inserimentoDB(informazioniProdotto):
    try:
        # connessione al DB
        sqliteConnection = sqlite3.connect('../Farmaci.db')
        cursor = sqliteConnection.cursor()
        print("Database connesso")

        cursor.execute(
            "SELECT * FROM tabella_farmaci WHERE minsan=?", (informazioniProdotto['minsan'],))
        data = cursor.fetchall()

        if len(data) == 0:
            # create --> Dato non ancora presente
            sql = """INSERT INTO tabella_farmaci
                          VALUES (?,?,?,?,?,?,?);"""

            dati = (informazioniProdotto['minsan'], informazioniProdotto['nomeProdotto'], informazioniProdotto['prezzoNuovo'],
                    informazioniProdotto['prezzoVecchio'], informazioniProdotto['descrizione'], informazioniProdotto['img'],
                    informazioniProdotto['categoria'])
            cursor.execute(sql, dati)
            sqliteConnection.commit()
            print('Farmaco creato correttamente')

        else:
            # controllo --> update
            if informazioniProdotto['prezzoNuovo'] < str(data[0][2]):
                # update
                sql_2 = """UPDATE tabella_farmaci SET nomeProdotto=?, prezzo=?, prezzoVecchio=?, descrizione=?, img=?, categoria=? WHERE minsan=?"""
                dati_2 = (informazioniProdotto['nomeProdotto'], informazioniProdotto['prezzoNuovo'],
                          informazioniProdotto['prezzoVecchio'], informazioniProdotto['descrizione'],
                          informazioniProdotto['img'], informazioniProdotto['categoria'], informazioniProdotto['minsan'])
                cursor.execute(sql_2, dati_2)
                sqliteConnection.commit()
                print('Farmaco aggiornato correttamente')

        cursor.close()

    except sqlite3.Error as error:
        print("Errore durante la connessione al DB", error)
    finally:
        if (sqliteConnection):
            sqliteConnection.close()
            print("Connessione chiusa")
