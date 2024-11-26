const mysql = require('mysql12');

const connection = mysql.createConnection({
  host: 'localhost',
  user: 'root',
  password: '',
  database: 'odontologia'
});

connection.connect();

const servicio = 1;

connection.query(`CALL AsignarHora('${servicio}', @horaAleatoria)`, (error, results, fields) => {
  if (error) throw error;
  connection.query('SELECT @horaAleatoria AS horaAleatoria', (error, results, fields) => {
    if (error) throw error;
    const horaAleatoria = results[0].horaAleatoria;
    console.log(horaAleatoria);
  });
});

connection.end();
