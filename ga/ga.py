import json
from pyeasyga import pyeasyga

def openfile(name):
    with open(name,'r') as InputData:
        data = []
        for line in InputData:
            data.append([float(i) for i in line.split()])
        MaxWeigth=data[0][0]
        MaxValume=data[0][1]
        data.pop(0)
    return MaxWeigth,MaxValume, data

def fitness(individual, data):
    weight, volume, price =0,0,0
    for (selected, item) in zip(individual, data):
        if selected:
            weight +=item[0]
            volume +=item[1]
            price +=item[2]
    if weight > MaxWeigth or volume > MaxValume:
        price=0
    return price

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


if __name__ == "__main__":
    MaxWeigth,MaxValume, data = openfile('10.txt')
    ga=pyeasyga.GeneticAlgorithm(data)
    ga.population_size=200
    ga.fitness_function = fitness
    ga.run()
    result = ga.best_individual()
    makeRasult(result)