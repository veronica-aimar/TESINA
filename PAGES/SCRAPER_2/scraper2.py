import requests
from bs4 import BeautifulSoup
import pandas as pd
from concurrent.futures import ThreadPoolExecutor
import threading

lock = threading.Lock()
listaInformazioniFarmaci = []
headers = {'User-Agent': 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/89.0.4389.82 Safari/537.36'}

def scrapingProdotto(farmaco):
    minsan = farmaco.find("div", class_="product-reference text-muted").findChildren("a")[0].text
    nomeProdotto = farmaco.find("h3", class_="h3 product-title").findChildren("a")[0].text
    img = farmaco.find("div", class_="thumbnail-container").findChildren("img")[0].get("data-src")
    
    prezzoNuovo = farmaco.find("span", class_="product-price").text
    if farmaco.find("span", class_="regular-price text-muted") != None:
        prezzoVecchio = farmaco.find("span", class_="regular-price text-muted").text
    else:
        prezzoVecchio = -1
    
    categoria = farmaco.find("div", class_="product-category-name text-muted").text
    descrizione = farmaco.find("div", class_="product-description-short").text

    # parsificazione
    prezzoNuovo = prezzoNuovo.replace(',', '')
    prezzoNuovo = prezzoNuovo.replace('€', '')

    if prezzoVecchio != -1:
        prezzoVecchio = prezzoVecchio.replace(',', '')
        prezzoVecchio = prezzoVecchio.replace('€', '')

    descrizione = descrizione.replace("\xa0", '')

    # inserimento delle informazioni in dizionario e poi lista
    # informazioniFarmaco = {'minsan': minsan, 'nomeProdotto': nomeProdotto, 'prezzo': prezzoNuovo, 'prezzoVecchio': prezzoVecchio, 'descrizione': 'descrizione', 'img': img, 'categoria': categoria}
    
    informazioniFarmaco = [int(minsan), nomeProdotto, int(prezzoNuovo), int(prezzoVecchio), descrizione, img, categoria]
    # sezione critica
    lock.acquire()
    listaInformazioniFarmaci.append(informazioniFarmaco)
    lock.release()

def invioRichieste(pagina):    
    k = requests.get('https://www.topfarmacia.it/3366-farmaci-da-banco?page=' + pagina).text
    soup=BeautifulSoup(k,'html.parser')
    listaFarmaci = soup.find_all("article", class_="product-miniature product-miniature-default product-miniature-grid product-miniature-layout-1 js-product-miniature")
    
    with ThreadPoolExecutor(max_workers=10) as executor:
        results = executor.map(scrapingProdotto, listaFarmaci)
    print(pagina)

def main():
    numeroPagine = 201
    listaNumeroPagine = []
    for x in range(1, numeroPagine):
        listaNumeroPagine.append(str(x))

    with ThreadPoolExecutor(max_workers=10) as executor:
        results = executor.map(invioRichieste, listaNumeroPagine)
    
    for elemento in listaInformazioniFarmaci:
        print(elemento)

if __name__ == '__main__':
    main()