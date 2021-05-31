import requests
from bs4 import BeautifulSoup
import pandas as pd
from concurrent.futures import ThreadPoolExecutor
import threading

lock = threading.Lock()
listaInformazioniFarmaci = []

headers = {'User-Agent': 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/89.0.4389.82 Safari/537.36'}

def scrapingProdotto(farmaco):
    nomeProdotto = farmaco.find("h3", class_="h3 product-title").findChildren("a")[0].text
    link = farmaco.find("h3", class_="h3 product-title").findChildren("a")[0].get("href")
    img = farmaco.find("div", class_="thumbnail-container").findChildren("a")[0].findChildren("img")[0].get("data-src")

    prezzoVecchio = farmaco.find("span", class_="regular-price text-muted")
    if prezzoVecchio == None:
        prezzoVecchio = -1
    else:
        prezzoVecchio = prezzoVecchio.text
    prezzoNuovo = farmaco.find("span", class_="product-price").text

    k = requests.get(link).text
    dettagliFarmaco = BeautifulSoup(k,'html.parser')

    minsan = dettagliFarmaco.find("span", {"itemprop": "sku"}).text
    
    descrizione = dettagliFarmaco.find("div", {"id": "description"})
    if descrizione == None:
        descrizione = 'Descrizione da modificare'
    else:
        descrizione = descrizione.findChildren("div", class_="rte-content")[0].text

    # parsificazione
    prezzoNuovo = prezzoNuovo.replace("€", '')
    prezzoNuovo = prezzoNuovo.replace(",", '')

    if prezzoVecchio != -1:
        prezzoVecchio = prezzoVecchio.replace("€", '')
        prezzoVecchio = prezzoVecchio.replace(",", '')

    descrizione = descrizione.replace("\xa0", '')
    descrizione = descrizione.replace("\n", '')

    # inserimento delle informazioni in dizionario e poi lista
    # informazioniFarmaco = {'minsan': minsan, 'nomeProdotto': nomeProdotto, 'prezzo': prezzoNuovo, 'prezzoVecchio': prezzoVecchio, 'descrizione': descrizione, 'img': img, 'categoria': 'Farmacia da banco'}
    informazioniFarmaco = [int(minsan), nomeProdotto, int(prezzoNuovo), int(prezzoVecchio), descrizione, img, 'Farmacia da banco']

    # sezione critica
    lock.acquire()
    print('Sezione critica')
    listaInformazioniFarmaci.append(informazioniFarmaco)
    lock.release()

def invioRichieste(pagina):    
    # scorrimento delle pagine
    k = requests.get('https://farmaciasavorani.it/farmaci/?page=' + str(pagina)).text
    soup=BeautifulSoup(k,'html.parser')
    listaFarmaci = soup.find_all("div", class_="js-product-miniature-wrapper")

    with ThreadPoolExecutor(max_workers=10) as executor:
        results = executor.map(scrapingProdotto, listaFarmaci)
    print(pagina)

def main():
    numeroPagine = 37
    listaNumeroPagine = []
    for x in range(1, numeroPagine):
        listaNumeroPagine.append(str(x))

    with ThreadPoolExecutor(max_workers=10) as executor:
        results = executor.map(invioRichieste, listaNumeroPagine)
    
    for elemento in listaInformazioniFarmaci:
        print(elemento)

if __name__ == '__main__':
    main()