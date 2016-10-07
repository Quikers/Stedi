using System;
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
using System.Windows.Input;
using System.Linq;

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
        string[] sortMethods = new string[] { "Title", "Most popular", "Least popular", "Highest rating", "Lowest rating", "Oldest", "Newest" };
        private int methodIndex = 0;
        List<Dictionary<string, string>> games = new List<Dictionary<string, string>>();
        List<Dictionary<string, string>> filteredGames = new List<Dictionary<string, string>>();
        private Process gameProcess = new Process();
        private int savedIndex = -1;
        Timer t;
        private int playTime = 0;
        private int currentPlayingGameIndex = 0;
        private int selectedControl = 0;
        private ListBox lbGames;
        private int mode = 0;

        // Rating window
        private Grid gridRating;
        private TextBox txtRatingMessage;
        private double ratingValue = 5;

        // Keyboard
        string[] keyChars = new string[] { "1", "2", "3", "4", "5", "6", "7", "8", "9", "0", "q", "w", "e", "r", "t", "y", "u", "i", "o", "p", "a", "s", "d", "f", "g", "h", "j", "k", "l", "z", "x", "c", "v", "b", "n", "m", "<-" };
        private Grid gridKeyboard = new Grid();
        private Label[] lblKey = new Label[37];
        private int keyindex = 0;
        
        public MainWindow()
        {
            InitializeComponent();

            Init();
        }

        private void Init() {
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

            // Show selected item
            TxtSearchbar.BorderThickness = new System.Windows.Thickness(2);
            TxtSearchbar.BorderBrush = Brushes.White;
            btnSort.BorderBrush = Brushes.White;
            lbGames.BorderBrush = Brushes.White;

            // Create popup window
            gridRating = new Grid();
            gridRating.Width = 500;
            gridRating.Height = 300;
            gridRating.HorizontalAlignment = HorizontalAlignment.Center;
            gridRating.VerticalAlignment = VerticalAlignment.Center;
            gridRating.Background = (Brush)new BrushConverter().ConvertFromString("#CC000000");
            gridRating.Visibility = Visibility.Hidden;

            txtRatingMessage = new TextBox();
            txtRatingMessage.Text = "Rate this game! < 2.5 >";
            txtRatingMessage.FontSize = 40;
            txtRatingMessage.HorizontalAlignment = HorizontalAlignment.Center;
            txtRatingMessage.VerticalAlignment = VerticalAlignment.Center;
            txtRatingMessage.FontWeight = FontWeights.Normal;
            txtRatingMessage.BorderThickness = new System.Windows.Thickness(0);
            txtRatingMessage.Foreground = (Brush)new BrushConverter().ConvertFromString("#FFFFFFFF");
            txtRatingMessage.Background = (Brush)new BrushConverter().ConvertFromString("#00000000");

            gridRating.Children.Add(txtRatingMessage);
            TheMainWindow.Children.Add(gridRating);

            // Create keyboard
            gridKeyboard.Width = 560;
            gridKeyboard.Height = 250;
            gridKeyboard.HorizontalAlignment = HorizontalAlignment.Center;
            gridKeyboard.VerticalAlignment = VerticalAlignment.Center;
            gridKeyboard.Background = (Brush)new BrushConverter().ConvertFromString("#CC000000");
            gridKeyboard.Visibility = Visibility.Hidden;

            for (int i = 0; i < keyChars.Length; i++) {
                lblKey[i] = new Label();
                lblKey[i].Content = keyChars[i];
                lblKey[i].Width = 45;
                lblKey[i].Height = 70;
                lblKey[i].FontSize = 40;
                lblKey[i].FontWeight = FontWeights.Normal;
                lblKey[i].VerticalAlignment = VerticalAlignment.Top;
                lblKey[i].HorizontalAlignment = HorizontalAlignment.Left;
                lblKey[i].Foreground = (Brush)new BrushConverter().ConvertFromString("#FFFFFFFF");
                lblKey[i].Background = (Brush)new BrushConverter().ConvertFromString("#00000000");
                lblKey[i].BorderThickness = new System.Windows.Thickness(0);
                int x = 0;
                int y = 0;
                if (i < 10) {
                    y = 0;
                    x = i;
                } else if (i < 20) {
                    y = 1;
                    x = i - 10;
                } else if (i < 29) {
                    y = 2;
                    x = i - 20;
                } else {
                    y = 3;
                    x = i - 29;
                }
                lblKey[i].Margin = new Thickness(20 + x * 50 + y * 25, 20 + y * 50, 1, 1);
                gridKeyboard.Children.Add(lblKey[i]);
            }

            TheMainWindow.Children.Add(gridKeyboard);

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

                    lbGames.Items.Add(name + Environment.NewLine + string.Join(" / ", game["tags"].Split(' ')) + Environment.NewLine + game["author"]); // WARNING this colum position might change
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
            LblCreated.Content = filteredGames[index]["author"];
            LblDate.Content = filteredGames[index]["created"].Split(' ')[0];

            // Set description 
            TxtDescription.Text = filteredGames[index]["description"].Replace("<br />", "");

            // Set rating
            if (GetRating(Convert.ToInt32(filteredGames[index]["id"])) == 0)
                LblRating.Content = "This game is not yet rated";
            else
                LblRating.Content = Math.Round(GetRating(Convert.ToInt32(filteredGames[index]["id"])), 1);

            // Set play count
            LblPlayCount.Content = filteredGames[index]["playcount"];

            // Set time played
            int seconds = Convert.ToInt32(filteredGames[index]["timeplayed"]);
            int minutes = seconds / 60;
            int hours = minutes / 60;
            seconds %= 60;
            minutes %= 60;
            LblPlayTime.Content = hours.ToString() + "h " + minutes.ToString() + "m " + seconds.ToString() + "s";

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
            
            SortGames();

            // Update view
            UpdateListbox();
        }

        private double GetRating(int gameid)
        {
            List<Dictionary<string, string>> rating = Database.Query("SELECT gameid, AVG(rating) AS avgrating FROM ratings WHERE gameid = " + gameid.ToString() + " GROUP BY gameid");
            if (rating.Count == 0)
                return 0.0;
            else
                return Convert.ToDouble(rating[0]["avgrating"]);
        }

        private void SortGames() {
            switch(methodIndex)
            {
                case 0:
                    // Title
                    games = games.OrderBy(o => o["name"]).ToList();
                    break;
                case 1:
                    // Most popular
                    games = games.OrderByDescending(o => o["playcount"]).ToList();
                    break;
                case 2:
                    // Least popular
                    games = games.OrderBy(o => o["playcount"]).ToList();
                    break;
                case 3:
                    // Highest rating
                    games = games.OrderByDescending(o => GetRating(Convert.ToInt32(o["id"]))).ToList();
                    break;
                case 4:
                    // Lowest rating
                    games = games.OrderBy(o => GetRating(Convert.ToInt32(o["id"]))).ToList();
                    break;
                case 5:
                    // Oldest
                    games = games.OrderBy(o => o["created"]).ToList();
                    break;
                case 6:
                    // Newest
                    games = games.OrderByDescending(o => o["created"]).ToList();
                    break;
            }
        }

        /// <summary>
        /// Gets the list of games from the database.
        /// </summary>
        private void GetGames()
        {
            // Add query to a MySqlCommand object
            games = Database.Query("SELECT * FROM games");

            // Sort games
            SortGames();
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
                string dir = @"\Games\" + filteredGames[i]["id"];
                // Check if game is activated
                if (Convert.ToInt16(filteredGames[i]["activated"]) != 1) {
                    // Game is not activated and will be removed from the list
                    filteredGames.RemoveAt(i--);

                } else if (Convert.ToInt16(filteredGames[i]["activated"]) == 2) {
                    if (!Directory.Exists(Directory.GetCurrentDirectory() + dir) && !File.Exists(Directory.GetCurrentDirectory() + dir + @"\game.exe")) {
                        // Game is not activated and will be removed from the list
                        filteredGames.RemoveAt(i--);
                    } else {
                        Database.Query("UPDATE `games` SET `activated`=1 WHERE id=" + filteredGames[i]["id"]);
                    }
                } else if (!Directory.Exists(Directory.GetCurrentDirectory() + dir) || !File.Exists(Directory.GetCurrentDirectory() + dir + @"\game.exe")) {
                    // Set game to activated but doesn't exist on hard drive
                    Database.Query("UPDATE `games` SET `activated` = 2 WHERE id=" + filteredGames[i]["id"]);

                    // Game is not activated and will be removed from the list
                    filteredGames.RemoveAt(i--);

                }
            }

            if (TxtSearchbar.Text.Trim(' ') == "") return;

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
        private void StartGame()
        {
            // Check if a game is already running
            if (playTime != 0) return;

            // Check if selected index is valid
            int index = lbGames.SelectedIndex;
            if (index < 0 || index >= games.Count) return;

            string filePath = Directory.GetCurrentDirectory() + @"\games\" + filteredGames[index]["id"] + @"\game.exe";

            // Run executable
            try
            { 
                gameProcess = new Process();
                gameProcess.EnableRaisingEvents = true;
                gameProcess.Exited += new EventHandler(Process_Exited);
                gameProcess.StartInfo.FileName = filePath;
                gameProcess.Start();
                playTime = Convert.ToInt32(DateTime.Now.Subtract(new DateTime(1970, 1, 1)).TotalMilliseconds / 1000);

                // Update play count
                Database.Query("UPDATE games SET playcount = playcount + 1 WHERE id = " + filteredGames[index]["id"]);

                currentPlayingGameIndex = index;
            } catch (Exception ex) {
                gameProcess = null;
                MessageBox.Show(ex.ToString() + Environment.NewLine + Environment.NewLine + "File: " + filePath);
            }
        }

        private void ShowRatingWindow()
        {
            gridRating.Visibility = Visibility.Visible;
        }

        private void HideRatingWindow()
        {
            gridRating.Visibility = Visibility.Hidden;
        }

        private void ShowKeyboard()
        {
            gridKeyboard.Visibility = Visibility.Visible;
        }

        private void HideKeyboard()
        {
            gridKeyboard.Visibility = Visibility.Hidden;
        }

        private void UpdateKeyboard()
        {
            for(int i=0; i<keyChars.Length; i++)
            {
                lblKey[i].BorderBrush = Brushes.White;
                lblKey[i].BorderThickness = new Thickness(0);
            }
            lblKey[keyindex].BorderThickness = new Thickness(2);
        }

        private void EnterKeyboard()
        {
            if (keyindex == keyChars.Length - 1)
            {
                if(TxtSearchbar.Text.Length > 0) TxtSearchbar.Text = TxtSearchbar.Text.Remove(TxtSearchbar.Text.Length - 1);
            }
            else TxtSearchbar.Text += keyChars[keyindex];
        }

        // Handle Exited event and display process information.
        private void Process_Exited(object sender, System.EventArgs e)
        {
            int elapsed = Convert.ToInt32(DateTime.Now.Subtract(new DateTime(1970, 1, 1)).TotalMilliseconds / 1000) - playTime;
            playTime = 0;

            // Update play count
            Database.Query("UPDATE games SET timeplayed = timeplayed + " + elapsed.ToString() + " WHERE id = " + filteredGames[currentPlayingGameIndex]["id"]);

            // Show rating window
            mode = 1;
            Dispatcher.Invoke(ShowRatingWindow);
        }

        /// <summary>
        /// Gets the games from the database every 2 seconds.
        /// </summary>
        private void t_elapsed(object sender, EventArgs e) {
            t.Stop();

            try {
                Dispatcher.Invoke(Update);
            } catch { } // Make sure program does not crash on exit

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

        // Input handler
        private void Stedi_PreviewKeyDown(object sender, KeyEventArgs e)
        {
            // Switch controls
            if (mode == 0) {
                if (e.Key == System.Windows.Input.Key.Escape) {
                    Environment.Exit(0);
                }
                if (e.Key == System.Windows.Input.Key.Up) {
                    selectedControl--;
                    if (selectedControl < 0) selectedControl = 2;
                }
                if (e.Key == System.Windows.Input.Key.Down) {
                    selectedControl++;
                    if (selectedControl > 2) selectedControl = 0;
                }

                // Sorting method
                if (selectedControl == 1) {
                    if (e.Key == System.Windows.Input.Key.Left) {
                        methodIndex--;
                        if (methodIndex < 0) methodIndex = sortMethods.Length - 1;
                    }
                    if (e.Key == System.Windows.Input.Key.Right) {
                        methodIndex++;
                        if (methodIndex >= sortMethods.Length) methodIndex = 0;
                    }

                    // Update text of button
                    btnSort.Content = sortMethods[methodIndex];

                    // Apply sort
                    Update();
                }

                // Selected game
                if (selectedControl == 2) {
                    if (e.Key == System.Windows.Input.Key.Left) {
                        if (lbGames.SelectedIndex == 0) lbGames.SelectedIndex = lbGames.Items.Count - 1;
                        else lbGames.SelectedIndex--;
                    }
                    if (e.Key == System.Windows.Input.Key.Right) {
                        if (lbGames.SelectedIndex == lbGames.Items.Count - 1) lbGames.SelectedIndex = 0;
                        else lbGames.SelectedIndex++;
                    }
                }

                if (e.Key == System.Windows.Input.Key.Enter) {
                    if (selectedControl == 0) {
                        // Show keyboard
                        mode = 2;
                        Dispatcher.Invoke(ShowKeyboard);
                    } else if (selectedControl == 2) StartGame();
                }
            } else if (mode == 1) {
                // Change rating value
                if (e.Key == System.Windows.Input.Key.Left) {
                    ratingValue -= 0.1;
                    if (ratingValue < 1) ratingValue = 1;
                }
                if (e.Key == System.Windows.Input.Key.Right) {
                    ratingValue += 0.1;
                    if (ratingValue > 5) ratingValue = 5;
                }

                // Rate and return to main menu
                if (e.Key == System.Windows.Input.Key.Enter) {
                    // Insert rating
                    Database.Query("INSERT INTO ratings (userid, gameid, rating) values(0, " +
                                   filteredGames[lbGames.SelectedIndex]["id"] + ", " +
                                   ratingValue.ToString().Replace(",", ".") + ")");

                    // Hide rating window
                    mode = 0;
                    ratingValue = 5;
                    Dispatcher.Invoke(HideRatingWindow);
                }

                // Update rating window
                txtRatingMessage.Text = "Rate this game! < " + ratingValue.ToString() + " >";
            } else if (mode == 2) {
                if (e.Key == System.Windows.Input.Key.Left) {
                    keyindex--;
                    if (keyindex < 0) keyindex = keyChars.Length - 1;
                }
                if (e.Key == System.Windows.Input.Key.Right) {
                    keyindex++;
                    if (keyindex > keyChars.Length - 1) keyindex = 0;
                    ;
                }
                if (e.Key == System.Windows.Input.Key.Up) {
                    if (keyindex < 10) {
                        keyindex += 29;
                        if (keyindex >= keyChars.Length) keyindex = keyChars.Length - 1;
                    } else if (keyindex < 20) {
                        keyindex -= 10;
                    } else if (keyindex < 29) {
                        keyindex -= 10;
                    } else {
                        keyindex -= 9;
                    }
                }
                if (e.Key == System.Windows.Input.Key.Down) {
                    if (keyindex < 10) {
                        keyindex += 10;
                    } else if (keyindex < 20) {
                        keyindex += 10;
                    } else if (keyindex < 29) {
                        keyindex += 9;
                    } else {
                        keyindex -= 26;
                    }
                }
                if (e.Key == System.Windows.Input.Key.Enter) {
                    Dispatcher.Invoke(EnterKeyboard);
                }
                if (e.Key == System.Windows.Input.Key.Escape) {
                    Dispatcher.Invoke(HideKeyboard);
                    mode = 0;
                }
                Dispatcher.Invoke(UpdateKeyboard);
            }

            // Update user interface
            TxtSearchbar.BorderThickness = new System.Windows.Thickness(0);
            btnSort.BorderThickness = new System.Windows.Thickness(0);
            lbGames.BorderThickness = new System.Windows.Thickness(0);

            if (selectedControl == 0)
            {
                TxtSearchbar.BorderThickness = new System.Windows.Thickness(2);
            }
            else if (selectedControl == 1)
            {
                btnSort.BorderThickness = new System.Windows.Thickness(2);
            }
            else if (selectedControl == 2)
            {
                lbGames.BorderThickness = new System.Windows.Thickness(2);
            }
            else
            {
                selectedControl = 0;
                TxtSearchbar.BorderThickness = new System.Windows.Thickness(2);
            }

            e.Handled = true;
        }
    }
}
