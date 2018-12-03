from SPARQLWrapper import SPARQLWrapper, JSON
from requests import get
import json


def getFilmUri(movie_title):
    json = get('https://www.wikidata.org/w/api.php', {
        'action': 'wbgetentities',
        'titles': movie_title,
        'sites': 'enwiki',
        'props': '',
        'format': 'json'
    }).json()
    result = list(json['entities'])[0]
    return result

def get_actors(filmUri):
    sparql = SPARQLWrapper("https://query.wikidata.org/sparql")
    sparql.setQuery("""#10
    SELECT ?actorPulp ?actorPulpLabel 
    WHERE {       
     wd:%s wdt:P161 ?actorPulp
     minus
    {  
    wd:Q104123 wdt:P161 ?actorPulp.
    ?film wdt:P31 wd:Q11424.
    ?film wdt:P161 ?actorPulp.
    ?film wdt:P577 ?date.
    filter(?date<"1994-05-12T00:00:00Z"^^xsd:dateTime)
    }     
    SERVICE wikibase:label { bd:serviceParam wikibase:language "[AUTO_LANGUAGE],en". }
    }""" % filmUri)
    sparql.setReturnFormat(JSON)
    return sparql.query().convert()


if __name__ == "__main__":
    filmUri = getFilmUri('Pulp Fiction')
    results = get_actors(filmUri)
    arr = []
    for result in results["results"]["bindings"]:
        arr.append(result["actorPulpLabel"]["value"])
    print(json.dumps(arr, indent=4))
    with open('Output.json', 'a')as outfile:
        outfile.write(json.dumps(arr, indent=4))