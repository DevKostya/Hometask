import fun
import json

Data_filepath="/InputData/data.csv"
Data=fun.cvs_to_array(Data_filepath)
DataContPlace_filepath="/InputData/context_place.csv"
DataContPlace=fun.cvs_to_array(DataContPlace_filepath)
DataContDay_filepath="/InputData/context_day.csv"
DataContDay=fun.cvs_to_array(DataContDay_filepath)

while True:
    User = input()
    if (fun.Check_int(User)): #Вводите значения, пока не будет число
        break

kNN=7
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


DictResult= {"user": User, "1" : {}, "2" : {}}
i=1
while i<len(ListRate):
    DictResult["1"]["movie " + str(ListRate[i])]=Rate[i]
    i=i+1
DictResult["2"]["movie " + str(MostRate.index(max(MostRate)))]=round(max(MostRate),3)
print(json.dumps(DictResult, indent=4))
with open('Output.json', 'w')as outfile:
    outfile.write(json.dumps(DictResult, indent=4))

