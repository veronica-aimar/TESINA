import sqlite3
import requests
from bs4 import BeautifulSoup
import pandas as pd
import concurrent.futures
from concurrent.futures import ThreadPoolExecutor
import threading

lock = threading.Lock()
listaInformazioniFarmaci = []
headers = {
    'User-Agent': 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/89.0.4389.82 Safari/537.36'}


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


def scrapingProdotto(farmaco):
    link = farmaco.find(
        "a", class_="product photo product-item-photo").get('href')

    prezzi = farmaco.find_all("span", class_="price")
    prezzoVecchio = prezzi[0].text
    if len(prezzi) > 1:
        prezzoNuovo = prezzi[1].text
    nomeProdotto = farmaco.find(
        "strong", class_="product name product-item-name").findChildren("a")[0].text.strip()

    img = farmaco.find(
        "span", class_="product-image-wrapper").findChildren("img")[0].get("src")

    k = requests.get(link).text
    dettagliSito = BeautifulSoup(k, 'html.parser')

    minsan = dettagliSito.find("span", class_="sku").findChildren()[1].text
    dettagliGenerali = dettagliSito.find(
        "div", class_="product attribute description")
    if dettagliGenerali != None:
        descrizione = dettagliGenerali.findChildren(
            "div", class_="value")[0].text

    categoria = dettagliSito.find(
        "span", class_="categoria_riferimento").findChildren("a")[0].text

    # parsificazione
    prezzoVecchio = prezzoVecchio.replace(',', '')
    prezzoNuovo = prezzoNuovo.replace(',', '')

    prezzoVecchio = prezzoVecchio.replace('€', '')
    prezzoNuovo = prezzoNuovo.replace('€', '')

    descrizione = descrizione.replace("\xa0", '')
    prezzoVecchio = prezzoVecchio.replace("\xa0", '')
    prezzoNuovo = prezzoNuovo.replace("\xa0", '')

    # inserimento delle informazioni in dizionario e poi lista
    # informazioniFarmaco = {'minsan': minsan, 'nomeProdotto': nomeProdotto, 'prezzo': prezzoNuovo, 'prezzoVecchio': prezzoVecchio, 'descrizione': descrizione, 'img': img, 'categoria': categoria}
    # informazioniFarmaco = [minsan, nomeProdotto, prezzoNuovo, prezzoVecchio, descrizione, img, categoria]

    print(nomeProdotto)
    # sezione critica
    lock.acquire()
    informazioniFarmaco = {'minsan': minsan, 'nomeProdotto': nomeProdotto, 'prezzoNuovo': prezzoNuovo,
                           'prezzoVecchio': prezzoVecchio, 'descrizione': descrizione, 'img': img, 'categoria': categoria}
    # print(informazioniFarmaco)
    listaInformazioniFarmaci.append(informazioniFarmaco)
    lock.release()


def invioRichieste(paginaIniziale, paginaFinale):
    # scorrimento delle pagine
    pagina = paginaIniziale

    while pagina <= paginaFinale:
        k = requests.get(
            'https://www.amafarma.com/farmaci-da-banco.html?p=' + str(pagina)).text
        soup = BeautifulSoup(k, 'html.parser')
        listaFarmaci = soup.find_all("li", class_="item product product-item")

        with ThreadPoolExecutor(max_workers=10) as executor:
            results = executor.map(scrapingProdotto, listaFarmaci)
        pagina = pagina + 1


def thread1():
    invioRichieste(1, 14)


def thread2():
    invioRichieste(15, 28)


def thread3():
    invioRichieste(29, 42)


def thread4():
    invioRichieste(43, 56)


def thread5():
    invioRichieste(57, 70)


def thread6():
    invioRichieste(71, 84)


def startScraping():
    with concurrent.futures.ThreadPoolExecutor(max_workers=6) as executor:
        executor.submit(thread1)
        executor.submit(thread2)
        executor.submit(thread3)
        executor.submit(thread4)
        executor.submit(thread5)
        executor.submit(thread6)


def main():
    startScraping()

    for informazioniFarmaco in listaInformazioniFarmaci:
        inserimentoDB(informazioniFarmaco)


if __name__ == '__main__':
    main()
