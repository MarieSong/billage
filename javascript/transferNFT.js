const contract = require('./connect'); // connect.js 모듈을 가져옴

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

// 사용 예제
 // const tokenId = 1; // 전송할 NFT의 토큰 ID
// const toAddress = '0xRecipientAddress'; // NFT를 전송할 대상 주소
// transferNFT(tokenId, toAddress, 'New rental history', 'New repair history');
