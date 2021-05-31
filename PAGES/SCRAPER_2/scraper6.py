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
    img = farmaco.find("img", class_="js-lazy-product-image").get("data-src")

    prezzoVecchio = farmaco.find("span", class_="regular-price")
    if prezzoVecchio != None:
        prezzoVecchio = prezzoVecchio.text
    else:
        prezzoVecchio = -1
    prezzoNuovo = farmaco.find("span", class_="product-price").text

    minsan = farmaco.find("div", class_="product-reference text-muted").text
    descrizione = farmaco.find("div", class_="product-description-short")
    if descrizione != None:
        descrizione = descrizione.text
    else:
        descrizione = ''
        
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
    listaInformazioniFarmaci.append(informazioniFarmaco)
    lock.release()

def invioRichieste(pagina):    
    # scorrimento delle pagine
    k = requests.get('https://farmaciasemplice.it/2085544-farmaci?page=' + str(pagina)).text
    soup=BeautifulSoup(k,'html.parser')
    listaFarmaci = soup.find_all("div", class_="js-product-miniature-wrapper")

    with ThreadPoolExecutor(max_workers=10) as executor:
        results = executor.map(scrapingProdotto, listaFarmaci)
    print(pagina)

def main():
    numeroPagine = 189
    listaNumeroPagine = []
    for x in range(1, numeroPagine):
        listaNumeroPagine.append(str(x))

    with ThreadPoolExecutor(max_workers=10) as executor:
        results = executor.map(invioRichieste, listaNumeroPagine)
    
    for elemento in listaInformazioniFarmaci:
        print(elemento)

if __name__ == '__main__':
    main()