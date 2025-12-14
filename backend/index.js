import express from "express";
import mysql from "mysql2";
import cors from "cors";
const app = express();
const port = 5500;

app.use(cors());
// Middleware to parse JSON requests
app.use(express.json());

const connection = mysql.createConnection({
  host: 'localhost',
  user: 'root',      
  password: 'cscpeboy12',  
  database: 'energyfm_cms'   
});

connection.connect(err => {
  if (err) {
    console.error('Error connecting to MySQL:', err);
    return;
  }
  console.log('Connected to MySQL database!');
});

// Define a GET route for the homepage
app.get('/', (req, res) => {
  res.send('Hello from Express.js Server!');
});

function fetchTable(res, tableName, columnName) {
  const query = columnName === '*' 
    ? `SELECT * FROM ${tableName}`
    : `SELECT ${columnName} FROM ${tableName}`;

  connection.query(query, (err, results) => {
    if (err) {
      res.status(500).send(err);
    } else {
      res.json(results);
    }
  });
}

app.get('/daytype', (req, res) => fetchTable(res, 'Day_Type', '*'));
app.get('/program_anchor_assignment', (req, res) => fetchTable(res, 'Program_Anchor_Assignment', '*'));
app.get('/programs', (req, res) => fetchTable(res, 'Program', '*'))
app.get('/djs', (req, res) => fetchTable(res, 'DJ_Profile', '*'))
app.get('/program_day_type', (req, res) => fetchTable(res, 'Program_Day_Type', '*'))

// Start the server
app.listen(port, () => {
  console.log(`Express server listening on port ${port}`);
});
