const express = require('express');
const path = require('path');
const app = express();

const PORT = 3000;
app.use(express.static(__dirname));
app.use(express.json());
app.use(express.urlencoded({ extended: true }));

const { MongoClient } = require('mongodb');
const uri = 'mongodb+srv://tassilo:Moser100@products.1mttb.mongodb.net/';
const dbName = 'TuftsBookClub';

let db;

MongoClient.connect(uri, { useNewUrlParser: true, useUnifiedTopology: true })
    .then(client => {
        console.log('Connected to MongoDB');
        db = client.db(dbName);
    })
    .catch(err => console.error('Failed to connect to MongoDB', err));


//API ROUTES////////////
app.get('/api/books', async (req, res) => {
    try {
        const collection = db.collection('Books');
        const items = await collection.find().toArray();
        res.json(items);
    } catch (err) {
        console.error('Error fetching data:', err);
        res.status(500).send('Error retrieving data');
    }
});

app.post('/api/checkout', async (req, res) => {
    try {
        const itemss = req.body.items; // Array of items with id, name, and price

        const collection = db.collection('Books');

        // Extract the list of IDs to delete
        const itemIds = itemss.map(item => item.name);

        console.log(itemIds);

        // Remove items by their `_id`
        const result = await collection.deleteMany({ book_name: { $in: itemIds } });

        res.status(200).send('success');

    } catch (err) {
        console.error('Error during checkout:', err);
        res.status(500).send('Error processing checkout.');
    }
});

// Start the server
// API ROUTES
app.post('/api/sell-book', async (req, res) => {
    try {
        const { bookName, author, condition, price, description } = req.body;

        const collection = db.collection('Books');

        const newBook = {
            book_name: bookName,
            author: author,
            condition: condition,
            price: parseFloat(price),
            description: description || '',
            created_at: new Date()
        };

        const result = await collection.insertOne(newBook);

        if (result.insertedId) {
            res.status(500).json({ error: 'Added the book to the database' });
        } else {
            res.status(500).json({ error: 'Failed to add the book to the database' });
        }
    } catch (err) {
        console.error('Error adding book:', err);
        res.status(500).send('Error adding book');
    }

});



//PUBLIC ROUTES////////////
app.get('/', (req, res) => {
    res.sendFile(path.join(__dirname, 'homepage.html'));
});

app.get('/about', (req, res) => {
    res.sendFile(path.join(__dirname, 'about.html'));
});

app.get('/blog', (req, res) => {
    res.sendFile(path.join(__dirname, 'blog.html'));
});

app.get('/bookstore', (req, res) => {
    res.sendFile(path.join(__dirname, 'bookstore.html'));
});

app.get('/sell-book', (req, res) => {
    res.sendFile(path.join(__dirname, 'sell-book.html'));
});

app.get('/contact', (req, res) => {
    res.sendFile(path.join(__dirname, 'contact.html'));
});

app.get('/donations', (req, res) => {
    res.sendFile(path.join(__dirname, 'donations.html'));
});

app.get('/resources', (req, res) => {
    res.sendFile(path.join(__dirname, 'donations.html'));
});

app.listen(PORT, () => {
    console.log(`Server is running on http://localhost:${PORT}`);
});