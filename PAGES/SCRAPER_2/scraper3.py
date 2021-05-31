import requests
from bs4 import BeautifulSoup
import pandas as pd
from concurrent.futures import ThreadPoolExecutor
import threading

lock = threading.Lock()
headers = {'User-Agent': 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/89.0.4389.82 Safari/537.36'}

listaInformazioniFarmaci = []

def scrapingProdotto(farmaco):
    link = farmaco.find("div", class_="product-meta").findChildren("div", class_="group-item")[0].findChildren("h3", class_="name")[0].findChildren("a")[0].get("href")
    nomeProdotto = farmaco.find("h3", class_="name").findChildren("a")[0].text

    if farmaco.find("span", class_="price-old") != None:
        prezzoNuovo = farmaco.find("span", class_="price-new").text
        prezzoVecchio = farmaco.find("span", class_="price-old").text
    else:
        prezzoNuovo = farmaco.find("div", class_="price").text
        prezzoVecchio = -1
        
    img = farmaco.find("div", class_="product-block").findChildren("div", class_="image")[0].findChildren("div", class_="face")[0].findChildren("a")[0].findChildren("img")[0].get("src")
    
    k = requests.get(link).text
    dettagliFarmaco = BeautifulSoup(k,'html.parser')
    
    minsan = dettagliFarmaco.find("span", {"itemprop": "sku"}).text
    descrizione = dettagliFarmaco.find("div", {"itemprop": "description"}).findChildren("p")[1].text
    if descrizione == 'Principi attivi' or descrizione == 'Eccipienti' or descrizione == 'Indicazioni terapeutiche' or descrizione == 'Controindicazioni' or descrizione == 'Posologia' or descrizione == 'Avvertenze e precauzioni' or descrizione == 'Interazioni' or descrizione == 'Effetti indesiderati':
        descrizione = dettagliFarmaco.find("div", {"itemprop": "description"}).findChildren("p")[2].text

    # parsificazione
    prezzoVecchio = prezzoVecchio.replace(',', '')
    prezzoNuovo = prezzoNuovo.replace(',', '')

    prezzoVecchio = prezzoVecchio.replace('€', '')
    prezzoNuovo = prezzoNuovo.replace('€', '')

    descrizione = descrizione.replace("\xa0", '')

    # inserimento delle informazioni in dizionario e poi lista
    # informazioniFarmaco = {'minsan': minsan, 'nomeProdotto': nomeProdotto, 'prezzo': prezzoNuovo, 'prezzoVecchio': prezzoVecchio, 'descrizione': descrizione, 'img': img, 'categoria': 'Farmacia da banco'}
    informazioniFarmaco = [int(minsan), nomeProdotto, int(prezzoNuovo), int(prezzoVecchio), descrizione, img, 'Farmacia da banco']
    listaInformazioniFarmaci.append(informazioniFarmaco)
    # print(listaInformazioniFarmaci)

    # sezione critica
    lock.acquire()
    print('Sezione critica')
    listaInformazioniFarmaci.append(informazioniFarmaco)
    lock.release()

def invioRichieste(pagina):    
    k = requests.get('https://farmacialoreto.it/farmaci?page=' + str(pagina)).text
    soup=BeautifulSoup(k,'html.parser')
    listaFarmaci = soup.find_all("div", class_="col-lg-3 col-md-3 col-sm-6 col-xs-12")

    with ThreadPoolExecutor(max_workers=10) as executor:
        results = executor.map(scrapingProdotto, listaFarmaci)
    print(pagina)

def main():
    numeroPagine = 133
    listaNumeroPagine = []
    for x in range(numeroPagine):
        listaNumeroPagine.append(str(x))

    with ThreadPoolExecutor(max_workers=10) as executor:
        results = executor.map(invioRichieste, listaNumeroPagine)
    
    for elemento in listaInformazioniFarmaci:
        print(elemento)

if __name__ == '__main__':
    main()