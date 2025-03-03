const express = require('express');
const { MongoClient } = require('mongodb');
const path = require('path');
const bodyParser = require('body-parser');
const app = express();

const uri = 'mongodb+srv://oliverzhang:NFhPZyJewjs1LHrU@cluster0.xmjge.mongodb.net/?retryWrites=true&w=majority&appName=Cluster0';
const client = new MongoClient(uri);

app.use(bodyParser.urlencoded({ extended: true }));
app.use(express.static('public'));

app.get('/', (req, res) => {
    res.sendFile(path.join(__dirname, 'public', 'home.html'));
});

app.get('/process', async (req, res) => {
    const search = req.query.search;
    const type = req.query.type;

    try {
        await client.connect();
        const database = client.db('Stock');
        const collection = database.collection('PublicCompanies');
        let query = {};

        if (type === 'company') {
            query = { company: new RegExp(search, 'i') };
        } else if (type === 'ticker') {
            query = { stockTicker: new RegExp(search, 'i') };
        }

        const results = await collection.find(query).toArray();

        if (results.length > 0) {
            res.send(`
                <h1>Search Results</h1>
                <ul>
                    ${results.map(result => `
                        <li>${result.company} (${result.stockTicker}): $${result.stockPrice}</li>
                    `).join('')}
                </ul>
                <br><a href="/">Go Back to Home</a>
            `);
        } else {
            res.send('No results found.<br><a href="/">Go Back to Home</a>');
        }
    } catch (error) {
        console.error('Error querying database:', error);
        res.status(500).send('Internal Server Error');
    } finally {
        await client.close();
    }
});

const port = process.env.PORT || 3000;
app.listen(port, () => {
    console.log(`Server is running on port ${port}`);
});
