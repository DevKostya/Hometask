import fun
import json

Data_filepath="/InputData/data.csv"
Data=fun.cvs_to_array(Data_filepath,1)
DataContPlace_filepath="/InputData/context_place.csv"
DataContPlace=fun.cvs_to_array(DataContPlace_filepath,1)
DataContDay_filepath="/InputData/context_day.csv"
DataContDay=fun.cvs_to_array(DataContDay_filepath,1)
DataFilmName_filepath="/InputData/Movie_names.csv"
DataFilmName=fun.cvs_to_array(DataFilmName_filepath,0)


with open('Output.json', "w")as outfile:
    pass

kNN=7
User=7
for User1 in range(1,2):
    ListRate=fun.list_of_not_rate(User,Data)
    SimArray=fun.sim(User,Data) #наиболее подходящие пользователи по оценке
    SimArrayPlace=fun.simPlaceDay(User,DataContPlace)#наиболее подходящие по месту
    SimArrayDay=fun.simPlaceDay(User,DataContDay)#наиболее подходящие по времени
    SimArrayAll=fun.Concat(SimArray,SimArrayPlace,SimArrayDay)
    SimArray=fun.sort(SimArray) #сортируем массив подходящих пользователей по оценке
    AvgArray=fun.AvgRate(Data) #массив средних значений для пользователей
    Rate=fun.Rate(AvgArray,SimArray,ListRate,Data,User,kNN)
    ListAll=[i+1 for i in range(len(Data[1])-1)]
    MostRate=fun.Rate(AvgArray,SimArray,ListAll,Data,User,kNN)

    #формуруем json
    DictResult= {"user": User, "1" : {}, "2" : {}, "3" : {}, "4" : {}}
    i=0
    while i<len(ListRate):
        DictResult["1"]["Movie " + str(ListRate[i])]=Rate[i]
        i=i+1
    DictResult["2"]["Movie " + str(MostRate.index(max(MostRate)))]=round(max(MostRate),3)
    DictResult["3"]["Movie " + str(MostRate.index(max(MostRate)))]=DataFilmName[MostRate.index(max(MostRate))-1][1]
    #Выбираем фильм
    filmUri = fun.getFilmUri(DataFilmName[MostRate.index(max(MostRate))-1][1])
    results = fun.get_actors(filmUri)
    arr = []
    for result in results["results"]["bindings"]:
        arr.append(result["actorPulpLabel"]["value"])
    DictResult["4"]["Actors: "] = arr
    print(json.dumps(DictResult, indent=4))
    with open('Output.json', 'a')as outfile:
        outfile.write(json.dumps(DictResult, indent=4))

