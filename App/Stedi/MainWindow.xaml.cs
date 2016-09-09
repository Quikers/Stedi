using System;
using System.Diagnostics;
using System.Windows;

// IO for checking and reading file
using System.IO;

// MySql
using MySql.Data;
using MySql.Data.MySqlClient;
using System.Collections.Generic;

namespace Stedi {
    public partial class MainWindow : Window {
        // Variables
        private static MySqlConnection MySqlConn;
        List<string[]> games;

        public MainWindow()
        {
            // Init
            // Create MySQL connection
            string MySqlConfig = "Server=localhost;Database=stedi;Uid=root;";
            try
            {
                MySqlConn = new MySqlConnection(MySqlConfig);
                MySqlConn.Open();
            }
            catch
            {
                // Couldn't connect to database. Show error message and close application
                MessageBox.Show("Couldn't connect to database.");
                Application.Current.Shutdown();
            }

            // MySQL connection created succesfully ready to start application window
            InitializeComponent();

            // Update while preparing the window
            update();
        }

        // Update listbox
        private void updateListbox(List<string[]> games)
        {
            // CLear listbox
            lbGames.Items.Clear();

            // Loop through all games and add them in the listbox
            for(int i=0; i<games.Count; i++)
            {
                lbGames.Items.Add(games[i][1]); // WARNING this colum position might change
            }

            // If there are any games select the first one
            if(lbGames.Items.Count > 0)
            {
                lbGames.SelectedIndex = 0;
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
            lblCreated.Content = "Created: " + games[index][5] + " " + games[index][4]; // WARNING this colum position might change

            // Set genre
            lblGenre.Content = "Genre: " + games[index][6]; // WARNING this colum position might change

            // Set description
            txtDescription.Text = games[index][7]; // WARNING this colum position might change

            // TODO: Get comments and show them
        }

        // Update
        private void update()
        {
            // Get games from the database
            games = getGames();

            // Filter games for valid directories, executables and if activated
            games = filterGames(games);

            // TODO: Check if some sorting method is specified and sort by default

            // Update listbox
            updateListbox(games);
        }

        // Get all games from the database
        private List<string[]> getGames()
        {
            // List to save the game information in
            List<string[]> games = new List<string[]>();

            // Add query to a MySqlCommand object
            string query = "SELECT * FROM games";
            MySqlCommand cmd = new MySqlCommand(query, MySqlConn);
            cmd.CommandType = System.Data.CommandType.Text;

            // Execute the query and save the result in the game list
            using (MySqlDataReader reader = cmd.ExecuteReader())
            {
                while(reader.Read())
                {
                    // Add a new string array
                    games.Add(new string[reader.FieldCount]);

                    // Set the values of the string array
                    for(int i=0; i<reader.FieldCount; i++)
                    {
                        games[games.Count - 1][i] = reader.GetString(i);
                    }
                }
            }
            
            return games;
        }

        // Filter game list
        private List<string[]> filterGames(List<string []> games)
        {
            // TODO: Check if returning the list is needed because games is probably passed by reference
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

            return games;
        }

        private void Stedi_Deactivated(object sender, EventArgs e) {
            Activate();
            Topmost = false; // important
            Topmost = true;  // important
            Focus();         // important
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
        private void btnRefresh_Click(object sender, RoutedEventArgs e)
        {
            update();
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
            updateListbox(games);
        }
    }
}
