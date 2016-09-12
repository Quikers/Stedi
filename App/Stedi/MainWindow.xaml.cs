using System;
using System.Diagnostics;
using System.Windows;
using System.Collections.Generic;

// IO for checking and reading file
using System.IO;
using System.Timers;
using System.Windows.Controls;
using System.Windows.Media;

namespace Stedi {
    public partial class MainWindow : Window {
        // Variables
        static List<string[]> games;
        static Timer t;

        private static ListBox lbGames = new ListBox();
        
        public MainWindow()
        {
            InitializeComponent();

            lbGames.HorizontalAlignment = HorizontalAlignment.Left;
            lbGames.Margin = new Thickness(10, 71, 0, 9.6);
            lbGames.Width = 200;
            lbGames.Background = (Brush)new BrushConverter().ConvertFromString("#28FFFFFF");
            lbGames.Foreground = Brushes.White;
            lbGames.BorderThickness = new Thickness(0);
            lbGames.SelectionChanged += lbGames_SelectionChanged;
            lbGames.FontFamily = new FontFamily("Segoe UI");

            t = new Timer(2000);
            t.Elapsed += t_elapsed;
            t.Start();

            // Update while preparing the window
            update();
        }

        // Update listbox
        private static void updateListbox()
        {
            try {
                // CLear listbox
                lbGames.Items.Clear();

                // Loop through all games and add them in the listbox
                for (int i = 0; i < games.Count; i++) {
                    lbGames.Items.Add(games[i][1]); // WARNING this colum position might change
                }

                // If there are any games select the first one
                if (lbGames.Items.Count > 0) {
                    lbGames.SelectedIndex = 0;
                }
            } catch (Exception ex) {
                MessageBox.Show(ex.ToString());
            }
        }

        // Update game information
        private void updateGameInfo(List<string []> games, int index)
        {
            // Check if index is a valid index
            if (index < 0 || index >= games.Count) return;

            // Set title
            lblName.Content = games[index][1]; // WARNING this colum position might change

            // Set created
            lblCreated.Content = "Created by: " + games[index][5] + " " + games[index][4]; // WARNING this colum position might change

            // Set genre
            lblGenre.Content = games[index][6]; // WARNING this colum position might change

            // Set description
            txtDescription.Text = games[index][7]; // WARNING this colum position might change

            // TODO: Get comments and show them
        }

        // Update
        private static void update()
        {
            // Get games from the database
            getGames();

            // Filter games for valid directories, executables and if activated
            filterGames();

            // TODO: Check if some sorting method is specified and sort by default

            // Update listbox
            updateListbox();
        }

        // Get all games from the database
        private static void getGames()
        {
            // List to save the game information in
            games = new List<string[]>();

            // Add query to a MySqlCommand object
            games = Database.query("SELECT * FROM games");

            return;
        }

        // Filter game list
        private static void filterGames()
        {
            // Loop through all the games
            for(int i=0; i<games.Count; i++)
            {
                // Check if game is activated by admin
                if(games[i][3] == "False")
                {
                    // Game is not activated and will be removed from the list
                    games.RemoveAt(i);

                    // Decrease i by 1 because the current index is removed and replaced by another one
                    i--;
                }

                // Check if the folder of the game exists
                // else if is used to prevent i from decreasing by more than one
                else if(!Directory.Exists(Directory.GetCurrentDirectory() + "\\games\\" + games[i][2])) // WARNING this colum position might change
                {
                    // Game is not activated and will be removed from the list
                    games.RemoveAt(i);

                    // Decrease i by 1 because the current index is removed and replaced by another one
                    i--;
                    
                    // TODO: Maybe report this error somewhere ???
                }

                // Check if executable exists
                else if (!File.Exists(Directory.GetCurrentDirectory() + "\\games\\" + games[i][2] + "\\game.exe")) // WARNING this colum position might change
                {
                    // Game is not activated and will be removed from the list
                    games.RemoveAt(i);

                    // Decrease i by 1 because the current index is removed and replaced by another one
                    i--;
                    
                    // TODO: Maybe report this error somewhere ???
                }
            }
        }

        // Update game info when a game is selected
        private void lbGames_SelectionChanged(object sender, System.Windows.Controls.SelectionChangedEventArgs e)
        {
            updateGameInfo(games, lbGames.SelectedIndex);
        }

        // Execute selected game executable
        private void btnPlay_Click(object sender, RoutedEventArgs e)
        {
            // Check if selected index is valid
            int index = lbGames.SelectedIndex;
            if (index < 0 || index >= games.Count) return;

            // Run executable
            System.Diagnostics.Process.Start(Directory.GetCurrentDirectory() + "\\games\\" + games[index][2] + "\\game.exe"); // WARNING this colum position might change
        }

        // Update everything
        private void t_elapsed(object sender, EventArgs e) {
            t.Stop();

            getGames();
            filterGames();
            updateListbox();

            t.Start();
        }

        private void txtSearchbar_KeyDown(object sender, System.Windows.Input.KeyEventArgs e)
        {
            // Check if enter is pressed
            if (e.Key != System.Windows.Input.Key.Enter) return; // Maybe remove this ?

            // First get full game list
            update();

            // When search bar is empty show everything
            if(txtSearchbar.Text == "")
            {
                update();
                return;
            }

            // String to search for
            string searchValue = txtSearchbar.Text.ToLower();

            // Loop through all the games
            for(int i=0; i<games.Count; i++)
            {
                // WARNING this colum positions might change
                if (!games[i][1].ToLower().Contains(searchValue) // Search in the name
                    && !games[i][5].ToLower().Contains(searchValue) // Search in the author
                    && !games[i][6].ToLower().Contains(searchValue) // Search in the genre
                    && !games[i][7].ToLower().Contains(searchValue)) // Search in the discription
                {
                    // When nothing is found remove from list
                    games.RemoveAt(i);

                    // Decrease i by 1 because the current index is removed and replaced by another one
                    i--;
                }
            }

            // Update view
            updateListbox();
        }
    }
}
