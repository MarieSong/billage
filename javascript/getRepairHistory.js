const contract = require('./connect'); // connect.js 모듈을 가져옴

async function getRepairHistoryLog(tokenId) {
    try {
        const result = await contract.methods.getRepairHistoryLog(tokenId).call();
        console.log('Repair History Log:', result);
    } catch (error) {
        console.error('Error fetching repair history log:', error);
    }
}

// 사용 예제
// const tokenIdToFetch = 1; // 조회할 NFT의 토큰 ID
// getRepairHistoryLog(tokenIdToFetch);
