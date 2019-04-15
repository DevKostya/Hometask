using System;
using System.Collections.Generic;
using System.IO;
using System.Linq;
using System.Text;
using System.Threading.Tasks;

namespace kNN
{
    // Класс Value для записи данных в список Data
    public class Value
    {
        public string[] Prop { get; set; }
        public string DClass { get; set; } 
    }
    // Класс значений об базе данных
    public class Info
    {
        public int DataLength { get; set; }
        public int DataInf { get; set; }
        public List<string> CountOfClasses { get; set; }

    }
    
    public class kNN
    {
        //точка входа
        public static void Main()
        {
            
        }

        //вывести данные о датасете
        public Info ReadData(List<Value> Data)
        {
            // количество в каждом классе
            var ClassData = from Value in Data
                group Value by Value.DClass
                into g
                select new { Name = g.Key, Count = g.Count() };
            List<string> CountClass = new List<string>();
            //группируем
            foreach (var group in ClassData)
                CountClass.Add(group.Name+": "+group.Count);
            Info Inf = new Info { DataLength = Data.Count, DataInf = Data[0].Prop.Length + 1, CountOfClasses = CountClass };
            return Inf;        
        }
        //Считываем данные с датасета      
        public List<Value> MakeData(List<Value> Data)
        {
            // Пытаемся считать файл
            using (StreamReader sr = new StreamReader("../../../iris.data"))
            {
                string line;
                // Считываем построчно
                while ((line = sr.ReadLine()) != null)
                {
                    // Разбиваем строку на значения по ,
                    String[] values = line.Split(new char[] { ',' }, StringSplitOptions.RemoveEmptyEntries);
                    string ValClas = values[values.Length - 1];
                    // удаляем последний элемент массива
                    values = values.Take(values.Count() - 1).ToArray();
                    Value DataLine = new Value { Prop = values, DClass = ValClas };
                    //добавляем в список массив свойств и класса
                    Data.Add(DataLine);
                }
            }
            return Data;
        }
        // добавить класс
        public void AddClass(String Str, List<Value> Data)
        {
            String[] values = Str.Split(new char[] { ',' }, StringSplitOptions.RemoveEmptyEntries);
            if (values.Count()==Data[0].Prop.Length+1)
            {
                string ValClas = values[values.Length - 1];
                values = values.Take(values.Count() - 1).ToArray();
                Value DataLine = new Value { Prop = values, DClass = ValClas };
                Data.Add(DataLine);
            }
        }
        // kNN
        public string ClassifyObjectKNN(string obj, List<Value> Data, int knn)
        {
            String[] values = obj.Split(new char[] { ',' }, StringSplitOptions.RemoveEmptyEntries);
            if (values.Count() == Data[0].Prop.Length)
            {
                double[] arrayNeigth = new double[knn];
                for(int k=0;k< arrayNeigth.Length; k++)
                {
                    arrayNeigth[k] = 1000;
                }
                string[] arrayNeigthClass = new string[knn];
                foreach (Value DataLine in Data)
                {
                    double sum = 0;
                    for(int i=0; i< DataLine.Prop.Length; i++)
                    {
                        sum = sum + Math.Pow((double.Parse(DataLine.Prop[i], System.Globalization.CultureInfo.InvariantCulture)- double.Parse(values[i], System.Globalization.CultureInfo.InvariantCulture)),2);
                    }
                    sum = Math.Pow(sum,0.5);
                    int j = 0;
                    while (j < arrayNeigth.Length && arrayNeigth[j] < sum) 
                    {
                        j++;
                    }
                    Console.WriteLine(j);
                    for (int z=arrayNeigth.Length-1; z > j; z--)
                    {
                        arrayNeigth[z] = arrayNeigth[z - 1];
                        arrayNeigthClass[z] = arrayNeigthClass[z - 1];
                    }
                    if (j < arrayNeigth.Length)
                    {
                        arrayNeigth[j] = sum;
                        arrayNeigthClass[j] = DataLine.DClass;
                    }
                    for (int z = 0; z < arrayNeigth.Length; z++)
                    {
                        Console.WriteLine(arrayNeigth[z] + " " + arrayNeigthClass[z]);
                    }

                }
                var result = arrayNeigthClass.GroupBy(x=>x)
                                             .OrderByDescending(x => x.Count())
                                             .Select(x => x.Key)
                                             .ToList();
                return result[0];
            }
            return "-1";
        }
    }
}
