import requests
from bs4 import BeautifulSoup
import pandas as pd
from concurrent.futures import ThreadPoolExecutor
import threading

lock = threading.Lock()
listaInformazioniFarmaci = []

def scrapingProdotto(farmaco):
    nomeProdotto = farmaco.find("a", class_="product-name").text
    img = farmaco.find("img", class_="img-responsive").get("src")

    prezzoVecchio = farmaco.find("span", class_="old-price product-price")
    if prezzoVecchio == None:
        prezzoVecchio = -1
    else:
        prezzoVecchio = prezzoVecchio.text
    prezzoNuovo = farmaco.find("span", class_="price product-price").text

    minsan = farmaco.find("a", class_="ajax_add_to_cart_button btn btn-danger btn-sm")
    if minsan == None:
        minsan = farmaco.find("div", class_="js-mrshopmailnotification").findChildren("input")[0].get("value")
    else:
        minsan = minsan.get("data-id-product")

    if len(minsan) < 9:
        aggiunta = ''
        for x in range(9 - len(minsan)):
            aggiunta = aggiunta + '0'
        minsan = aggiunta + minsan

    descrizione = farmaco.find("p", {"itemprop": "description"}).text

    # parsificazione
    prezzoNuovo = prezzoNuovo.replace(" Prezzo  IVA inclusa € ", '')
    prezzoNuovo = prezzoNuovo.replace(" ", '')
    prezzoNuovo = prezzoNuovo.replace(",", '')

    if prezzoVecchio != -1:
        prezzoVecchio = prezzoVecchio.replace(" € ", '')
        prezzoVecchio = prezzoVecchio.replace(",", '')

    descrizione = descrizione.replace("\xa0", '')
    descrizione = descrizione.replace('\n', '')
    descrizione = descrizione.replace('                ', '')

    # inserimento delle informazioni in dizionario e poi lista
    # informazioniFarmaco = {'minsan': minsan, 'nomeProdotto': nomeProdotto, 'prezzo': prezzoNuovo, 'prezzoVecchio': prezzoVecchio, 'descrizione': descrizione, 'img': img, 'categoria': 'Farmacia da banco'}
    informazioniFarmaco = [int(minsan), nomeProdotto, int(prezzoNuovo), int(prezzoVecchio), descrizione, img, 'Farmacia da banco']
    
    # sezione critica
    lock.acquire()
    listaInformazioniFarmaci.append(informazioniFarmaco)
    lock.release()

def invioRichieste(pagina):    
    # scorrimento delle pagine
    k = requests.get('https://www.farmacieravenna.com/7-farmaci#/page-' + str(pagina)).text
    soup=BeautifulSoup(k,'html.parser')
    listaFarmaci = soup.find_all("li", class_="ajax_block_product")

    with ThreadPoolExecutor(max_workers=10) as executor:
        results = executor.map(scrapingProdotto, listaFarmaci)
    print(pagina)

def main():
    numeroPagine = 75
    listaNumeroPagine = []
    for x in range(1, numeroPagine):
        listaNumeroPagine.append(str(x))

    with ThreadPoolExecutor(max_workers=10) as executor:
        results = executor.map(invioRichieste, listaNumeroPagine)
    
    for elemento in listaInformazioniFarmaci:
        print(elemento)

if __name__ == '__main__':
    main()