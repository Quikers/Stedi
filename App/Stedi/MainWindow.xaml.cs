using System;
using System.Diagnostics;
using System.Windows;
using System.Collections.Generic;
using System.Globalization;
using System.Windows.Controls;
using System.Windows.Media;

// IO for checking and reading file
using System.IO;

using Timer = System.Timers.Timer;

namespace Stedi {
    enum comboBoxItems {
        mostPopular =   0,
        leastPopular =  1,
        highestRating = 2,
        lowestRating =  3,
        oldest =        4,
        newest =        5
    }

    public partial class MainWindow : Window {
        // Variables
        List<string[]> games = new List<string[]>();
        private Process gameProcess = new Process();
        private comboBoxItems cbItems = new comboBoxItems();
        private int savedIndex = -1;
        Timer t;

        private ListBox lbGames = new ListBox();
        
        public MainWindow()
        {
            InitializeComponent();

            Fonts.GetFontFamilies(new Uri("pack://application:,,,/resources/fonts/#"));

            lbGames.HorizontalAlignment = HorizontalAlignment.Left;
            lbGames.SetValue(ScrollViewer.HorizontalScrollBarVisibilityProperty, ScrollBarVisibility.Disabled);
            lbGames.Margin = new Thickness(10, 81, 0, 9.6);
            lbGames.Width = 400;
            lbGames.Background = (Brush)new BrushConverter().ConvertFromString("#28FFFFFF");
            lbGames.Foreground = Brushes.White;
            lbGames.BorderThickness = new Thickness(0);
            lbGames.SelectionChanged += lbGames_SelectionChanged;
            lbGames.FontFamily = new FontFamily("Segoe UI Light");
            lbGames.FontSize = 20;

            TheMainWindow.Children.Add(lbGames);

            t = new Timer(2000);
            t.Elapsed += t_elapsed;
            t.Start();

            // Update while preparing the window
            update();
        }

        /// <summary>
        /// Creates a string with the correct width so it won't overflow the textbox.
        /// </summary>
        /// <param name="str"></param>
        /// <returns></returns>
        private Size MeasureString(string str) {
            var formattedText = new FormattedText(
                str,
                CultureInfo.CurrentUICulture,
                FlowDirection.LeftToRight,
                new Typeface(new FontFamily("Segoe UI"), FontStyles.Normal, FontWeights.Regular, FontStretches.Normal), 20, Brushes.Black);

            return new Size(formattedText.Width, formattedText.Height);
        }

        /// <summary>
        /// Updates lbGames' content.
        /// </summary>
        private void updateListbox()
        {
            try {
                // CLear listbox
                lbGames.Items.Clear();

                // Loop through all games and add them in the listbox
                for (int i = 0; i < games.Count; i++) {
                    string name = games[i][1];
                    int charWidth = 9;

                    // Check if text length is bigger than listbox width
                    if (lbGames.Width - 10 < MeasureString(name).Width) {
                        int ofWidth = (int)(MeasureString(name).Width - lbGames.Width);
                        ofWidth = (ofWidth - ofWidth % charWidth) / charWidth + 3;
                        name = name.Substring(0, name.Length - ofWidth) + "...";
                    }

                    lbGames.Items.Add(name); // WARNING this colum position might change
                }

                // If there are any games select the first one
                if (lbGames.Items.Count > 0) {
                    lbGames.SelectedIndex = savedIndex != -1 ? savedIndex : 0;
                }
            } catch (Exception ex) {
                MessageBox.Show(ex.ToString());
            }
        }

        /// <summary>
        /// Updates the on-screen information about the specified game.
        /// </summary>
        /// <param name="index">The index of the selected game</param>
        private void updateGameInfo(int index)
        {
            // Check if index is a valid index
            if (index < 0 || index >= games.Count) return;

            // Set title
            lblName.Content = games[index][1]; // WARNING this colum position might change

            // Set genre
            List<string> genres = new List<string>(games[index][4].Split(' '));
            genres.Sort();
            lblGenre.Content = string.Join(" / ", genres);

            // Set created
            lblCreated.Content = "Created by: " + games[index][5]; // WARNING this colum position might change
            lblDate.Content = "Release date: " + games[index][3].Split(' ')[0]; // WARNING this colum position might change

            // Set description
            txtDescription.Text = games[index][6]; // WARNING this colum position might change
        }

        /// <summary>
        /// All updates combined into one function.
        /// </summary>
        private void update() {
            savedIndex = lbGames.SelectedIndex;

            // Get games from the database
            getGames();

            // Filter games for valid directories, executables and if activated
            filterGames();

            string name = "Space Pirates: The Return of the Flip Flop";
            if (lbGames.Width - 10 < MeasureString(name).Width) {
                int ofWidth = (int)(MeasureString(name).Width - lbGames.Width);
                ofWidth = (ofWidth - ofWidth % 9) / 9 + 3;
                name = name.Substring(0, name.Length - ofWidth) + "...";
            }
            lbGames.Items.Add(name);

            // TODO: Check if some sorting method is specified and sort by default
        }

        /// <summary>
        /// Gets the list of games from the database.
        /// </summary>
        private void getGames()
        {
            // Add query to a MySqlCommand object
            games = Database.query("SELECT * FROM games");
        }
        
        /// <summary>
        /// Filters all games by category and the search bar.
        /// </summary>
        private void filterGames() {
            Directory.CreateDirectory(Directory.GetCurrentDirectory() + @"\Games");

            // Loop through all the games
            for (int i = 0; i < games.Count; i++)
            {
                // Check if game is activated
                if(Convert.ToInt16(games[i][2]) == 0)
                {
                    // Game is not activated and will be removed from the list
                    games.RemoveAt(i);

                    // Decrease i by 1 because the current index is removed and replaced by another one
                    i--;
                } else if(!Directory.Exists(Directory.GetCurrentDirectory() + @"\Games\" + games[i][0])) {
                    // Game is not activated and will be removed from the list
                    games.RemoveAt(i);

                    // Set game to activated but doesn't exist on hard drive
                    Database.query("UPDATE `games` SET `activated` = 2");

                    // Decrease i by 1 because the current index is removed and replaced by another one
                    i--;
                } else if (!File.Exists(Directory.GetCurrentDirectory() + @"\Games\" + games[i][0] + @"\Game.exe")) {
                    // Game.exe does not exist and will be removed from the list
                    games.RemoveAt(i);

                    // Set game to activated but doesn't exist on hard drive
                    Database.query("UPDATE `games` SET `activated` = 2");

                    // Decrease i by 1 because the current index is removed and replaced by another one
                    i--;
                }
            }

            if (txtSearchbar.Text.Trim(' ') != "") {

                // String to search for
                string searchValue = txtSearchbar.Text.ToLower();

                // Loop through all the games
                for (int i = 0; i < games.Count; i++) {
                    // WARNING this colum positions might change
                    if (!games[i][1].ToLower().Contains(searchValue) // Search in the name
                        && !games[i][4].ToLower().Contains(searchValue) // Search in the author
                        && !games[i][5].ToLower().Contains(searchValue) // Search in the genre
                        && !games[i][6].ToLower().Contains(searchValue)) // Search in the discription
                    {
                        // When nothing is found remove from list
                        games.RemoveAt(i);

                        // Decrease i by 1 because the current index is removed and replaced by another one
                        i--;
                    }
                }
            }

            // Update view
            updateListbox();
        }

        /// <summary>
        /// Update game info when a game is selected
        /// </summary>
        private void lbGames_SelectionChanged(object sender, SelectionChangedEventArgs e)
        {
            updateGameInfo(lbGames.SelectedIndex);
        }

        /// <summary>
        /// Executes the selected game.
        /// </summary>
        private void btnPlay_Click(object sender, RoutedEventArgs e)
        {
            // Check if selected index is valid
            int index = lbGames.SelectedIndex;
            if (index < 0 || index >= games.Count) return;

            // Run executable
            gameProcess = Process.Start(Directory.GetCurrentDirectory() + @"\games\" + games[index][2] + @"\game.exe");
        }

        /// <summary>
        /// Gets the games from the database every 2 seconds.
        /// </summary>
        private void t_elapsed(object sender, EventArgs e) {
            t.Stop();

            try {
                Dispatcher.Invoke(update);
            } catch (Exception ex) { }

            t.Start();
        }

        /// <summary>
        /// Searches the lbGames for the specified game.
        /// </summary>
        private void txtSearchbar_KeyDown(object sender, System.Windows.Input.KeyEventArgs e)
        {
            update();
        }

        private void cbCategory_SelectionChanged(object sender, SelectionChangedEventArgs e) {

        }
    }
}
