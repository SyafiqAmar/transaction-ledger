const express = require('express');
const ccxt = require('ccxt');
require('dotenv').config();

const app = express();
app.use(express.json());

const SUPPORTED_EXCHANGES = ['binance', 'bybit', 'indodax'];

function getExchange(exchangeId, credentials = {}) {
    if (!SUPPORTED_EXCHANGES.includes(exchangeId)) {
        throw new Error('Exchange not supported');
    }
    const ExchangeClass = ccxt[exchangeId];
    const exchange = new ExchangeClass(credentials);
    exchange.setSandboxMode(true);
    return exchange;
}

app.get('/ticker/:exchange/:symbol', async (req, res) => {
    try {
        const { exchange, symbol } = req.params;
        const pair = symbol.replace('-', '/'); 
        const ex = getExchange(exchange);
        const ticker = await ex.fetchTicker(pair);
        res.json({ symbol: pair, price: ticker.last });
    } catch (err) {
        res.status(400).json({ error: err.message });
    }
});

app.post('/balance/:exchange', async (req, res) => {
    try {
        const { exchange } = req.params;
        const { apiKey, apiSecret } = req.body;
        const ex = getExchange(exchange, { apiKey, secret: apiSecret });
        const balance = await ex.fetchBalance();
        res.json(balance.total); 
    } catch (err) {
        res.status(400).json({ error: err.message });
    }
});

const PORT = process.env.PORT || 3000;
app.listen(PORT, () => console.log(`Crypto service jalan di port ${PORT}`));
