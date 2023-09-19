const contract = require('./connect'); // connect.js 모듈을 가져옴

async function createNFT(serialNumber, name, model, rentalHistory, repairHistory) {
    const accounts = await web3.eth.getAccounts();
    const ownerAccount = accounts[0]; // 호출자의 계정 주소

    try {
        const result = await contract.methods.createNFT(serialNumber, name, model, rentalHistory, repairHistory).send({
            from: ownerAccount,
        });

        console.log(`Transaction Hash: ${result.transactionHash}`);
    } catch (error) {
        console.error('Error creating NFT:', error);
    }
}

// createNFT('123456', 'Device1', 'ModelA', 'Rented previously', 'No repair history');