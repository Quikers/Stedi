using System;
using System.Diagnostics;
using System.Windows;

namespace Stedi {

    /// <summary>
    /// Interaction logic for MainWindow.xaml
    /// </summary>
    public partial class MainWindow : Window {
        public MainWindow() {
            InitializeComponent();
        }

        private void Stedi_Deactivated(object sender, EventArgs e) {
            Activate();
            Topmost = false; // important
            Topmost = true;  // important
            Focus();         // important
        }
    }
}
