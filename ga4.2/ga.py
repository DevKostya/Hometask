import json
import operator
from random import randint

def openfile(name):
    with open(name,'r') as InputData:
        data = []
        for line in InputData:
            data.append([float(i) for i in line.split()])
        MaxWeigth=data[0][0]
        MaxValume=data[0][1]
        data.pop(0)
    return MaxWeigth,MaxValume, data



def makeRasult(result):
    resultVolume = 0
    resultWeigth = 0
    resultItems = []
    for i in range(len(result[1])):
        if result[1][i] == 1:
            resultItems.append(i + 1)
    for i in range(len(resultItems)):
        resultVolume += data[resultItems[i] - 1][1]
        resultWeigth += data[resultItems[i] - 1][0]
    jsonResult = dict(value=result[0], weigth=resultWeigth, volume=resultVolume, items=resultItems)
    with open('result.json', 'w') as OutData:
        json.dump(jsonResult, OutData, indent=4)



#1.1 random generation
def get_first_popul():
    Sets=[]
    for j in range(Count_sets):
        list=[]
        for i in range(len(data)):
            bit=randint(0,1) ##exist object or not
            list.append(bit)
        Sets.append(list)
    return Sets

#2.2 20% of the best
def filter_20():
    listPrice = {}
    listofBest=[]
    for id,j in enumerate(Sets):
        Volume, Weigth, Price = 0, 0, 0
        for i in range(len(j)):
            if (j[i] == 1):
                Volume += data[i][1]
                Weigth += data[i][0]
                Price += data[i][2]
        if (Volume > MaxValume or Weigth > MaxWeigth):
            Price = 0
        listPrice[id]=Price
    listPrice=sorted(listPrice.items(), key=operator.itemgetter(1), reverse=True)
    maxValue=listPrice[0][1]
    for i in range(Count_sets//5):
        listofBest.append(listPrice[i][0])
    return listofBest, maxValue

#3.2 self, random bit from his parents
def crossingover():
    children=[]
    for j in range(0,len(listofBest),2):
        child=[]
        child1 = []
        for i in range(len(Sets[listofBest[j]])):
            bit=randint(0,1)
            if (bit==0):
                child.append(Sets[listofBest[j]][i])
            else:
                child.append(Sets[listofBest[j+1]][i])
            bit = randint(0, 1)
            if (bit == 0):
                child1.append(Sets[listofBest[j]][i])
            else:
                child1.append(Sets[listofBest[j + 1]][i])
        children.append(child)
        children.append(child1)
    return children

#4.1 mutation 1 set invert
def mutatuin():
    index=randint(0, Count_sets-1) #0<=index<=199
    for i in range(len(Sets[index])):
        Sets[index][i]=(Sets[index][i]+1)%2

#5.3 replace parents on children
def replacePonC():
    for id,i in enumerate(listofBest):
        Sets[i]=children[id]



if __name__ == "__main__":
    MaxWeigth,MaxValume, data = openfile('10.txt')
    Count_sets=200
    Sets=get_first_popul()

 #max value for first popul

    for i in range(500):
        listofBest, maxValue=filter_20()
        try:
            lastMax
        except NameError:
            lastMax=maxValue
        if (abs(maxValue-lastMax)/(maxValue+lastMax)*2<=0.2):
            children=crossingover()
            mutatuin()
            replacePonC()
            lastMax=maxValue
        else:
            break
    result=[]
    result.append(maxValue)
    result.append(Sets[listofBest[0]])
    makeRasult(result)