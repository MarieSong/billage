const contract = require('./connect'); // connect.js 모듈을 가져옴

async function getRentalHistoryLog(tokenId) {
    try {
        const result = await contract.methods.getRentalHistoryLog(tokenId).call();
        console.log('Rental History Log:', result);
    } catch (error) {
        console.error('Error fetching rental history log:', error);
    }
}

// 사용 예제
// const tokenIdToFetch = 1; // 조회할 NFT의 토큰 ID
// getRentalHistoryLog(tokenIdToFetch);
