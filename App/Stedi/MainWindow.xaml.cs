﻿using System;
using System.Collections;
using System.Diagnostics;
using System.Windows;
using System.Collections.Generic;
using System.Globalization;
using System.Windows.Controls;
using System.Windows.Media;

// IO for checking and reading file
using System.IO;

using Timer = System.Timers.Timer;
using System.Windows.Media.Imaging;
using System.Text.RegularExpressions;

namespace Stedi {
    enum ComboBoxItems {
        MostPopular =   0,
        LeastPopular =  1,
        HighestRating = 2,
        LowestRating =  3,
        Oldest =        4,
        Newest =        5
    }

    public partial class MainWindow : Window {
        // Variables
        string[] sortMethods = new string[] { "Most popular", "Least popular", "Highest rating", "Lowest rating", "Oldest", "Newest" };
        UInt16 methodIndex = 0;
        List<Dictionary<string, string>> games = new List<Dictionary<string, string>>();
        List<Dictionary<string, string>> filteredGames = new List<Dictionary<string, string>>();
        private Process gameProcess = new Process();
        private int savedIndex = -1;
        Timer t;

        private ListBox lbGames;
        
        public MainWindow()
        {
            InitializeComponent();

            lbGames = new ListBox {
                HorizontalAlignment = HorizontalAlignment.Left,
                Margin = new Thickness(20, 99, 0, 20),
                Width = 390,
                Background = (Brush)new BrushConverter().ConvertFromString("#B3000000"),
                Foreground = Brushes.White,
                BorderThickness = new Thickness(0),
                FontFamily = new FontFamily("Segoe UI Light"),
                FontSize = 20
            };

            lbGames.SetValue(ScrollViewer.HorizontalScrollBarVisibilityProperty, ScrollBarVisibility.Disabled);
            lbGames.SelectionChanged += lbGames_SelectionChanged;

            TheMainWindow.Children.Add(lbGames);

            t = new Timer(2000);
            t.Elapsed += t_elapsed;
            t.Start();

            // Update while preparing the window
            Update();
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
        private void UpdateListbox()
        {
            try {
                // Clear listbox
                lbGames.Items.Clear();

                // Loop through all games and add them in the listbox
                foreach (Dictionary<string, string> game in filteredGames) {
                    string name = game["name"];
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
        private void UpdateGameInfo(int index)
        {
            // Check if index is a valid index
            if (index < 0 || index >= filteredGames.Count) return;

            // Set title
            LblName.Content = filteredGames[index]["name"];

            // Set tags
            List<string> tags = new List<string>(filteredGames[index]["tags"].Split(' '));
            tags.Sort();
            LblTag.Content = string.Join(" / ", tags);

            // Set created
            LblCreated.Content = "Created by: " + filteredGames[index]["author"];
            LblDate.Content = "Release date: " + filteredGames[index]["created"].Split(' ')[0];

            // Set description
            TxtDescription.Text = filteredGames[index]["description"];

            // Set image background
            byte[] binaryData = Convert.FromBase64String(Regex.Match(filteredGames[index]["background"], @"data:image/(?<type>.+?),(?<data>.+)").Groups["data"].Value);

            BitmapImage bi = new BitmapImage();
            bi.BeginInit();
            bi.StreamSource = new MemoryStream(binaryData);
            bi.EndInit(); 
            TheMainWindow.Background = new ImageBrush(bi);
            TheMainWindow.Background.Opacity = 0.5;
        }

        /// <summary>
        /// All updates combined into one function.
        /// </summary>
        private void Update() {
            savedIndex = lbGames.SelectedIndex;
           
            // Get games from the database
            GetGames();

            //PrintGamesArray();
            // Filter games for valid directories, executables and if activated
            filteredGames.Clear();
            filteredGames = null;
            filteredGames = new List<Dictionary<string, string>>(games);
            FilterGames();

            // TODO: Check if some sorting method is specified and sort by default
            SortGames();

            // Update view
            UpdateListbox();
        }

        private void SearchUpdate()
        {
            savedIndex = lbGames.SelectedIndex;

            //PrintGamesArray();
            // Filter games for valid directories, executables and if activated
            filteredGames.Clear();
            filteredGames = null;
            filteredGames = new List<Dictionary<string, string>>(games);

            FilterGames();

            //Convert.

            // TODO: Check if some sorting method is specified and sort by default
            SortGames();

            // Update view
            UpdateListbox();
        }

        private void SortGames() {
            // ============================== ZET HIER DE CATEGORY SORT ==============================
        }

        /// <summary>
        /// Gets the list of games from the database.
        /// </summary>
        private void GetGames()
        {
            // Add query to a MySqlCommand object
            games = Database.Query("SELECT * FROM games");
        }

        private void PrintGamesArray() {
            foreach (Dictionary<string, string> game in games) {
                string joinedString = "";

                foreach (KeyValuePair<string, string> field in game) {
                    if (field.Key == "background") continue;
                    joinedString += field.Key + "=" + field.Value + "\r\n";
                }

                MessageBox.Show(joinedString);
            }
        }
        
        /// <summary>
        /// Filters all games by category and the search bar.
        /// </summary>
        private void FilterGames() {
            Directory.CreateDirectory(Directory.GetCurrentDirectory() + @"\Games");

            // Loop through all the games
            for (int i = 0; i < filteredGames.Count; i++) {
                // Check if game is activated
                if (Convert.ToInt16(filteredGames[i]["activated"]) == 0) {
                    // Game is not activated and will be removed from the list
                    filteredGames.RemoveAt(i);

                    // Decrease i by 1 because the current index is removed and replaced by another one
                    i--;
                } else if (!Directory.Exists(Directory.GetCurrentDirectory() + @"\Games\" + filteredGames[i]["id"])) {
                    // Game is not activated and will be removed from the list
                    filteredGames.RemoveAt(i);

                    // Set game to activated but doesn't exist on hard drive
                    Database.Query("UPDATE `games` SET `activated` = 2");

                    // Decrease i by 1 because the current index is removed and replaced by another one
                    i--;
                } else if (!File.Exists(Directory.GetCurrentDirectory() + @"\Games\" + filteredGames[i]["id"] + @"\game.exe")) {
                    // Game.exe does not exist and will be removed from the list
                    filteredGames.RemoveAt(i);

                    // Set game to activated but doesn't exist on hard drive
                    Database.Query("UPDATE `games` SET `activated` = 2");

                    // Decrease i by 1 because the current index is removed and replaced by another one
                    i--;
                }
            }

            if (TxtSearchbar.Text.Trim(' ') != "") {

                // String to search for
                string searchValue = TxtSearchbar.Text.ToLower();

                // Loop through all the games
                for (int i = 0; i < filteredGames.Count; i++) {
                    // WARNING this colum positions might change
                    if (filteredGames[i]["name"].ToLower().Contains(searchValue) ||
                        filteredGames[i]["author"].ToLower().Contains(searchValue) ||
                        filteredGames[i]["tags"].ToLower().Contains(searchValue) ||
                        filteredGames[i]["description"].ToLower().Contains(searchValue)) continue;
                    // When nothing is found remove from list
                    filteredGames.RemoveAt(i);

                    // Decrease i by 1 because the current index is removed and replaced by another one
                    i--;
                }
            }
        }

        /// <summary>
        /// Update game info when a game is selected
        /// </summary>
        private void lbGames_SelectionChanged(object sender, SelectionChangedEventArgs e)
        {
            UpdateGameInfo(lbGames.SelectedIndex);
        }

        /// <summary>
        /// Executes the selected game.
        /// </summary>
        private void btnPlay_Click(object sender, RoutedEventArgs e)
        {
            // Check if selected index is valid
            int index = lbGames.SelectedIndex;
            if (index < 0 || index >= games.Count) return;

            string filePath = Directory.GetCurrentDirectory() + @"\games\" + games[index]["id"] + @"\game.exe";

            // Run executable
            try {
                gameProcess = Process.Start(filePath);
            } catch (Exception ex) {
                gameProcess = null;
                MessageBox.Show(ex.ToString() + Environment.NewLine + Environment.NewLine + "File: " + filePath);
            }
        }

        /// <summary>
        /// Gets the games from the database every 2 seconds.
        /// </summary>
        private void t_elapsed(object sender, EventArgs e) {
            t.Stop();

            try {
                Dispatcher.Invoke(Update);
            } catch (Exception ex) { } // Make sure program does not crash on exit

            t.Start();
        }
        

        // Update
        private void cbCategory_SelectionChanged(object sender, SelectionChangedEventArgs e) {
            Update();
        }

        /// <summary>
        /// Searches the lbGames for the specified game.
        /// </summary>
        private void txtSearchbar_TextChanged(object sender, TextChangedEventArgs e)
        {
            SearchUpdate();
        }
    }
}
