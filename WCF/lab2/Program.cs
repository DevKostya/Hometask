using System;
using System.ServiceModel;
using System.ServiceModel.Description;
using System.Threading.Tasks;
using kNN;
using System.Collections.Generic;

namespace lab2
{
    class host
    {
        public static List<Value> Data = new List<Value>();
        public static int CountRequest = 0;
        public static int CountClient = 0;
        public static int knn = 7;
        readonly kNN.kNN kNN = new kNN.kNN();
        [ServiceContract]
        public interface IStringService
        {
            [OperationContract]
            Info ReadData();
            [OperationContract]
            void MakeData();
            [OperationContract]
            void AddClass(String str);
            [OperationContract]
            void IncreaseRequests();
            [OperationContract]
            int CountRequests();
            [OperationContract]
            void IncreaseClients();
            [OperationContract]
            void DecreaseClients();
            [OperationContract]
            int CountClients();
            [OperationContract]
            string ClassifyObject(string obj);
        }

        public class StringService : IStringService
        {
            kNN.kNN kNN = new kNN.kNN();
            public Info ReadData()
            {
                Info Inf = kNN.ReadData(Data);
                return Inf;
            }
            public void MakeData()
            {
                kNN.MakeData(Data);
            }
            public void AddClass(String str)
            {
                kNN.AddClass(str,Data);
            }
            public void IncreaseRequests()
            {
                CountRequest++;
            }
            public int CountRequests()
            {
                return CountRequest;
            }
            public void IncreaseClients()
            {
                CountClient++;
            }
            public void DecreaseClients()
            {
                CountClient--;
            }
            public int CountClients()
            {
                return CountClient;
            }
            public string ClassifyObject(string obj)
            {
                return kNN.ClassifyObjectKNN(obj,Data,knn);
            }
        }

        class Program
        {
            static void Main(string[] args)
            {
                ServiceHost host = new ServiceHost(typeof(StringService), new Uri("http://localhost:8080/StringService"));
                // Добавляем конечную точку службы с заданным интерфейсом, привязкой (создаём новую) и адресом конечной точки
                host.AddServiceEndpoint(typeof(IStringService), new BasicHttpBinding(), "");
                // добавление точки "mex"
                ServiceMetadataBehavior behavior = new ServiceMetadataBehavior();  // создаем объект с метаданными
                behavior.HttpGetEnabled = true;  // разрешаем получение метаданных с помощью HTTP/GET запроса
                host.Description.Behaviors.Add(behavior);  // добаляем экземпляр в поведение службы
                // добавляем конечную точку(контракт,привязка и адрес конечной точки)
                host.AddServiceEndpoint(typeof(IMetadataExchange), MetadataExchangeBindings.CreateMexHttpBinding(), "mex");
                //создаем конфигурацию с клиентом 
                // Запускаем службу
                host.Open();
                kNN.kNN kNN = new kNN.kNN();
                Data=kNN.MakeData(Data);
                Console.WriteLine("Server is working..");
                Console.ReadKey();
                // Закрываем службу
                host.Close();
            }
        }
    }
}
