from SPARQLWrapper import SPARQLWrapper, JSON
import json

def get_actors():
    sparql = SPARQLWrapper("https://query.wikidata.org/sparql")
    sparql.setQuery("""#10
    #Фильм Криминальное чтиво(Pulp fiction)
    SELECT ?actorPulp ?actorPulpLabel 
    WHERE {       
     wd:Q104123 wdt:P161 ?actorPulp
     minus
    {  
    wd:Q104123 wdt:P161 ?actorPulp.
    ?film wdt:P31 wd:Q11424.
    ?film wdt:P161 ?actorPulp.
    ?film wdt:P577 ?date.
    filter(?date<"1994-05-12T00:00:00Z"^^xsd:dateTime)
    }     
    SERVICE wikibase:label { bd:serviceParam wikibase:language "[AUTO_LANGUAGE],en". }
    }""")
    sparql.setReturnFormat(JSON)
    return sparql.query().convert()


if __name__ == "__main__":
    results = get_actors()
    arr = []
    for result in results["results"]["bindings"]:
        arr.append(result["actorPulpLabel"]["value"])
    print(json.dumps(arr, indent=4))