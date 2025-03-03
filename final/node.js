const MongoClient = require('mongodb').MongoClient;
const connStr = "mongodb+srv://tassilo:<Moser100>@products.1mttb.mongodb.net/?retryWrites=true&w=majority&appName=Products"
client = new MongoClient(connStr);

async function findit() {
    try {
        await client.connect();
        var dbo = client.db("TuftsBookClub");
        var coll = dbo.collection('Books');

        theQuery = { author: "Stephen King" }
        await coll.find(theQuery, { title: 1 }).toArray(
            async function (err, items) {
                if (err) {
                    console.log("Error: " + err);
                } else {
                    console.long("Items: ");
                    await items.forEach(function (item) {
                        console.log(item.title + 'by' + item.author);
                    })
                }
                client.close();
            }
        )


    }
    catch (err) {
        console.long("Database error: " + err);
    }
}
