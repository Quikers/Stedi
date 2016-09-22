using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;
using System.Windows;
using MySql.Data;
using MySql.Data.MySqlClient;

namespace Stedi {
    static class Database {
        private static MySqlConnection MySqlConn;

        /// <summary>
        /// Static database class to get information from the Stedi database.
        /// </summary>
        static Database() {
            // Init
            // Create MySQL connection
            string MySqlConfig = "Server=localhost;Database=stedi;Uid=root;";
            try {
                MySqlConn = new MySqlConnection(MySqlConfig);
                MySqlConn.Open();
            } catch {
                // Couldn't connect to database. Show error message and close application
                MessageBox.Show("Couldn't connect to database.");
                Environment.Exit(0);
            }
        }

        /// <summary>
        /// Sends a request to the Stedi database to retrieve information.
        /// </summary>
        /// <param name="sql">The SQL code to send</param>
        /// <returns></returns>
        public static List<Dictionary<string, string>> Query(string sql) {
            List<Dictionary<string, string>> items = new List<Dictionary<string, string>>();

            MySqlCommand cmd = new MySqlCommand(sql, MySqlConn);
            cmd.CommandType = System.Data.CommandType.Text;

            try {
                // Execute the query and save the result in the game list
                using (MySqlDataReader reader = cmd.ExecuteReader()) {
                    while (reader.Read()) {
                        // Set the values of the string array
                        for (int i = 0; i < reader.FieldCount; i++) {
                            items[items.Count - 1][i] = reader.GetString(i);
                        }
                    }
                }
            } catch (Exception ex) {
                MessageBox.Show("Something went wrong with the database connection.\nStats for nerds:\n\n" + ex);
            }

            return items;
        }
    }
}
