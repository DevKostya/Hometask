using System;
using System.Collections.Generic;
using System.ComponentModel;
using System.Data;
using System.Drawing;
using System.Linq;
using System.Text;
using System.Threading.Tasks;
using System.Windows.Forms;
using Client.ServiceReference1;

namespace Client
{
    public partial class ClientForm : Form
    {
        private StringServiceClient client;
        public ClientForm()
        {
            client = new StringServiceClient();
            InitializeComponent();
        }
        // считать и вывести значения
        private async void OnClickInf(object sender, EventArgs e)
        {
            var result = await client.ReadDataAsync();
            Label1.Text = ("Количество данных: ") + result.DataLength;
            Label2.Text = ("Количество свойств + класс: ") + result.DataInf;
            Label3.Text = ("");
            foreach (var a in result.CountOfClasses)
            {
                Label3.Text = Label3.Text + a+ "\r";
            }
            await client.IncreaseRequestsAsync();
        }
        //Добавляем значение в массив   
        private async void button2_Click(object sender, EventArgs e)
        {
            await client.AddClassAsync(textBox1.Text);
            await client.IncreaseRequestsAsync();
        }

        private async void button3_Click(object sender, EventArgs e)
        {
            await client.IncreaseRequestsAsync();
            Label4.Text = ("Количество пользователей: ") + await client.CountClientsAsync();
            Label5.Text = ("Количество запросов: ")+ await client.CountRequestsAsync();
        }
        private async void ClientForm_Load(object sender, EventArgs e)
        {
            await client.IncreaseClientsAsync();
        }
        private async void ClientForm_FormClosing(object sender, FormClosingEventArgs e)
        {
            await client.DecreaseClientsAsync();
        }

        private async void button4_Click(object sender, EventArgs e)
        {
            string answer = await client.ClassifyObjectAsync(textBox2.Text);
            if (answer == "-1")
            {
                Label6.Text = ("Ошибка(формат ввода a,b,c,d)");
            }
            else
            {
                Label6.Text = ("Больше похоже на: ") + answer;
            }
        }
    }
}

