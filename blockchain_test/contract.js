const contract = require('./connect2.js'); // connect.js 모듈을 가져옴

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

async function transferNFT(tokenId, toAddress, newRentalHistory, repairHistory) {
    const accounts = await web3.eth.getAccounts();
    const ownerAccount = accounts[0]; // 호출자의 계정 주소

    try {
        const result = await contract.methods.transferNFT(tokenId, toAddress, newRentalHistory, repairHistory).send({
            from: ownerAccount,
        });

        console.log(`Transaction Hash: ${result.transactionHash}`);
    } catch (error) {
        console.error('Error transferring NFT:', error);
    }
}

async function getDeviceData(tokenId) {
    try {
        const result = await contract.methods.getDeviceData(tokenId).call();
        console.log('Device Data:', result);
    } catch (error) {
        console.error('Error fetching device data:', error);
    }
}

async function getRentalHistoryLog(tokenId) {
    try {
        const result = await contract.methods.getRentalHistoryLog(tokenId).call();
        console.log('Rental History Log:', result);
    } catch (error) {
        console.error('Error fetching rental history log:', error);
    }
}

async function getRepairHistoryLog(tokenId) {
    try {
        const result = await contract.methods.getRepairHistoryLog(tokenId).call();
        console.log('Repair History Log:', result);
    } catch (error) {
        console.error('Error fetching repair history log:', error);
    }
}
