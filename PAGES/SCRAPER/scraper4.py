import requests
from bs4 import BeautifulSoup
import pandas as pd
from concurrent.futures import ThreadPoolExecutor
import threading

lock = threading.Lock()
listaInformazioniFarmaci = []

def scrapingProdotto(farmaco):
    nomeProdotto = farmaco.find("h2", class_="product-name").text
    link = farmaco.find("h2", class_="product-name").findChildren("a")[0].get("href")
    img = farmaco.find("a", class_="product-image").findChildren("img")[0].get("src")
    descrizione = farmaco.find("div", class_="desc std").text

    prezzoVecchio = farmaco.find("span", class_="price")
    if prezzoVecchio == None:
        prezzoVecchio = -1
    else:
        prezzoVecchio = prezzoVecchio.text
    prezzoNuovo = farmaco.find("p", class_="special-price").findChildren("span", class_="price")[0].text

    k = requests.get(link).text
    dettagliFarmaco = BeautifulSoup(k,'html.parser')
    titolo = dettagliFarmaco.find("dl", class_="farma-details").findChildren("dt")[0].text
    if titolo == 'Sku: ':
        minsan = dettagliFarmaco.find("dl", class_="farma-details").findChildren("dd")[0].text
    else:
        minsan = dettagliFarmaco.find("dl", class_="farma-details").findChildren("dd")[1].text
    
    # parsificazione
    prezzoVecchio = prezzoVecchio.replace(',', '')
    prezzoNuovo = prezzoNuovo.replace(',', '')

    prezzoVecchio = prezzoVecchio.replace('€', '')
    prezzoNuovo = prezzoNuovo.replace('€', '')

    descrizione = descrizione.replace("\xa0", '')
    descrizione = descrizione.replace('\n', '')
    descrizione = descrizione.replace('                ', '')

    # inserimento delle informazioni in dizionario e poi lista
    # informazioniFarmaco = {'minsan': minsan, 'nomeProdotto': nomeProdotto, 'prezzo': prezzoNuovo, 'prezzoVecchio': prezzoVecchio, 'descrizione': descrizione, 'img': img, 'categoria': 'Farmacia da banco'}
    informazioniFarmaco = [int(minsan), nomeProdotto, int(prezzoNuovo), int(prezzoVecchio), descrizione, img, 'Farmacia da banco']
    listaInformazioniFarmaci.append(informazioniFarmaco)

    # sezione critica
    lock.acquire()
    print('Sezione critica')
    listaInformazioniFarmaci.append(informazioniFarmaco)
    lock.release()

def invioRichieste(pagina):    
    # scorrimento delle pagine
    k = requests.get('https://www.farmaciadifiducia.com/it/farmaci-da-banco-on-line/?p=' + str(pagina)).text
    soup=BeautifulSoup(k,'html.parser')
    listaFarmaci = soup.find_all("li", class_="item")

    with ThreadPoolExecutor(max_workers=10) as executor:
        results = executor.map(scrapingProdotto, listaFarmaci)
    print(pagina)

def main():
    headers = {'User-Agent': 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/89.0.4389.82 Safari/537.36'}
    k = requests.get('https://www.farmaciadifiducia.com/it/farmaci-da-banco-on-line/').text
    soup=BeautifulSoup(k,'html.parser')
    numeroPagine = soup.find("a", class_="last").text

    listaNumeroPagine = []
    for x in range(1, int(numeroPagine)):
        listaNumeroPagine.append(str(x))

    with ThreadPoolExecutor(max_workers=10) as executor:
        results = executor.map(invioRichieste, listaNumeroPagine)
    
    for elemento in listaInformazioniFarmaci:
        print(elemento)

if __name__ == '__main__':
    main()