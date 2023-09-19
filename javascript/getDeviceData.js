const contract = require('./connect'); // connect.js 모듈을 가져옴

async function getDeviceData(tokenId) {
    try {
        const result = await contract.methods.getDeviceData(tokenId).call();
        console.log('Device Data:', result);
    } catch (error) {
        console.error('Error fetching device data:', error);
    }
}

// 사용 예제
// const tokenIdToFetch = 1; // 조회할 NFT의 토큰 ID
// getDeviceData(tokenIdToFetch);
