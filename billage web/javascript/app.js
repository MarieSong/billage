// Web3.js 설정
const web3 = new Web3(window.ethereum);

// HTML 요소 가져오기
const createNFTForm = document.getElementById('createNFTForm');
const tokenIdElement = document.getElementById('token_id');
const transferNFTButton = document.getElementById('transferNFT');
const transferStatusElement = document.getElementById('transferStatus');
const getDeviceDataButton = document.getElementById('getDeviceDataButton');
const deviceDataElement = document.getElementById('deviceData');

// 스마트 계약 ABI (계약 인터페이스) 및 주소를 지역 변수로 정의
const contractABI = [
    {
        "inputs": [],
        "stateMutability": "nonpayable",
        "type": "constructor"
    },
    {
        "anonymous": false,
        "inputs": [
            {
                "indexed": true,
                "internalType": "address",
                "name": "owner",
                "type": "address"
            },
            {
                "indexed": true,
                "internalType": "address",
                "name": "approved",
                "type": "address"
            },
            {
                "indexed": true,
                "internalType": "uint256",
                "name": "tokenId",
                "type": "uint256"
            }
        ],
        "name": "Approval",
        "type": "event"
    },
    {
        "anonymous": false,
        "inputs": [
            {
                "indexed": true,
                "internalType": "address",
                "name": "owner",
                "type": "address"
            },
            {
                "indexed": true,
                "internalType": "address",
                "name": "operator",
                "type": "address"
            },
            {
                "indexed": false,
                "internalType": "bool",
                "name": "approved",
                "type": "bool"
            }
        ],
        "name": "ApprovalForAll",
        "type": "event"
    },
    {
        "inputs": [
            {
                "internalType": "address",
                "name": "to",
                "type": "address"
            },
            {
                "internalType": "uint256",
                "name": "tokenId",
                "type": "uint256"
            }
        ],
        "name": "approve",
        "outputs": [],
        "stateMutability": "nonpayable",
        "type": "function"
    },
    {
        "inputs": [
            {
                "internalType": "string",
                "name": "deviceName",
                "type": "string"
            },
            {
                "internalType": "string",
                "name": "modelName",
                "type": "string"
            },
            {
                "internalType": "string",
                "name": "serialNumber",
                "type": "string"
            }
        ],
        "name": "createDeviceNFT",
        "outputs": [
            {
                "internalType": "uint256",
                "name": "",
                "type": "uint256"
            }
        ],
        "stateMutability": "nonpayable",
        "type": "function"
    },
    {
        "anonymous": false,
        "inputs": [
            {
                "indexed": true,
                "internalType": "uint256",
                "name": "tokenId",
                "type": "uint256"
            },
            {
                "indexed": true,
                "internalType": "address",
                "name": "creator",
                "type": "address"
            }
        ],
        "name": "NFTCreated",
        "type": "event"
    },
    {
        "anonymous": false,
        "inputs": [
            {
                "indexed": true,
                "internalType": "uint256",
                "name": "tokenId",
                "type": "uint256"
            },
            {
                "indexed": true,
                "internalType": "address",
                "name": "from",
                "type": "address"
            },
            {
                "indexed": true,
                "internalType": "address",
                "name": "to",
                "type": "address"
            }
        ],
        "name": "NFTTransferred",
        "type": "event"
    },
    {
        "inputs": [
            {
                "internalType": "address",
                "name": "from",
                "type": "address"
            },
            {
                "internalType": "address",
                "name": "to",
                "type": "address"
            },
            {
                "internalType": "uint256",
                "name": "tokenId",
                "type": "uint256"
            }
        ],
        "name": "safeTransferFrom",
        "outputs": [],
        "stateMutability": "nonpayable",
        "type": "function"
    },
    {
        "inputs": [
            {
                "internalType": "address",
                "name": "from",
                "type": "address"
            },
            {
                "internalType": "address",
                "name": "to",
                "type": "address"
            },
            {
                "internalType": "uint256",
                "name": "tokenId",
                "type": "uint256"
            },
            {
                "internalType": "bytes",
                "name": "data",
                "type": "bytes"
            }
        ],
        "name": "safeTransferFrom",
        "outputs": [],
        "stateMutability": "nonpayable",
        "type": "function"
    },
    {
        "inputs": [
            {
                "internalType": "address",
                "name": "operator",
                "type": "address"
            },
            {
                "internalType": "bool",
                "name": "approved",
                "type": "bool"
            }
        ],
        "name": "setApprovalForAll",
        "outputs": [],
        "stateMutability": "nonpayable",
        "type": "function"
    },
    {
        "anonymous": false,
        "inputs": [
            {
                "indexed": true,
                "internalType": "address",
                "name": "from",
                "type": "address"
            },
            {
                "indexed": true,
                "internalType": "address",
                "name": "to",
                "type": "address"
            },
            {
                "indexed": true,
                "internalType": "uint256",
                "name": "tokenId",
                "type": "uint256"
            }
        ],
        "name": "Transfer",
        "type": "event"
    },
    {
        "inputs": [
            {
                "internalType": "uint256",
                "name": "tokenId",
                "type": "uint256"
            },
            {
                "internalType": "address",
                "name": "recipient",
                "type": "address"
            },
            {
                "internalType": "string[]",
                "name": "rentalHistory",
                "type": "string[]"
            },
            {
                "internalType": "string[]",
                "name": "repairHistory",
                "type": "string[]"
            }
        ],
        "name": "transferDeviceNFT",
        "outputs": [],
        "stateMutability": "nonpayable",
        "type": "function"
    },
    {
        "inputs": [
            {
                "internalType": "address",
                "name": "from",
                "type": "address"
            },
            {
                "internalType": "address",
                "name": "to",
                "type": "address"
            },
            {
                "internalType": "uint256",
                "name": "tokenId",
                "type": "uint256"
            }
        ],
        "name": "transferFrom",
        "outputs": [],
        "stateMutability": "nonpayable",
        "type": "function"
    },
    {
        "inputs": [
            {
                "internalType": "address",
                "name": "owner",
                "type": "address"
            }
        ],
        "name": "balanceOf",
        "outputs": [
            {
                "internalType": "uint256",
                "name": "",
                "type": "uint256"
            }
        ],
        "stateMutability": "view",
        "type": "function"
    },
    {
        "inputs": [
            {
                "internalType": "uint256",
                "name": "tokenId",
                "type": "uint256"
            }
        ],
        "name": "getApproved",
        "outputs": [
            {
                "internalType": "address",
                "name": "",
                "type": "address"
            }
        ],
        "stateMutability": "view",
        "type": "function"
    },
    {
        "inputs": [
            {
                "internalType": "uint256",
                "name": "tokenId",
                "type": "uint256"
            }
        ],
        "name": "getDeviceData",
        "outputs": [
            {
                "components": [
                    {
                        "internalType": "string",
                        "name": "deviceName",
                        "type": "string"
                    },
                    {
                        "internalType": "string",
                        "name": "modelName",
                        "type": "string"
                    },
                    {
                        "internalType": "string",
                        "name": "serialNumber",
                        "type": "string"
                    },
                    {
                        "internalType": "string[]",
                        "name": "rentalHistory",
                        "type": "string[]"
                    },
                    {
                        "internalType": "string[]",
                        "name": "repairHistory",
                        "type": "string[]"
                    }
                ],
                "internalType": "struct DeviceNFT.TokenData",
                "name": "",
                "type": "tuple"
            }
        ],
        "stateMutability": "view",
        "type": "function"
    },
    {
        "inputs": [
            {
                "internalType": "uint256",
                "name": "tokenId",
                "type": "uint256"
            }
        ],
        "name": "getRentalHistory",
        "outputs": [
            {
                "internalType": "string[]",
                "name": "",
                "type": "string[]"
            }
        ],
        "stateMutability": "view",
        "type": "function"
    },
    {
        "inputs": [
            {
                "internalType": "uint256",
                "name": "tokenId",
                "type": "uint256"
            }
        ],
        "name": "getRepairHistory",
        "outputs": [
            {
                "internalType": "string[]",
                "name": "",
                "type": "string[]"
            }
        ],
        "stateMutability": "view",
        "type": "function"
    },
    {
        "inputs": [
            {
                "internalType": "uint256",
                "name": "index",
                "type": "uint256"
            }
        ],
        "name": "getTokenIdByIndex",
        "outputs": [
            {
                "internalType": "uint256",
                "name": "",
                "type": "uint256"
            }
        ],
        "stateMutability": "view",
        "type": "function"
    },
    {
        "inputs": [
            {
                "internalType": "address",
                "name": "owner",
                "type": "address"
            },
            {
                "internalType": "address",
                "name": "operator",
                "type": "address"
            }
        ],
        "name": "isApprovedForAll",
        "outputs": [
            {
                "internalType": "bool",
                "name": "",
                "type": "bool"
            }
        ],
        "stateMutability": "view",
        "type": "function"
    },
    {
        "inputs": [],
        "name": "name",
        "outputs": [
            {
                "internalType": "string",
                "name": "",
                "type": "string"
            }
        ],
        "stateMutability": "view",
        "type": "function"
    },
    {
        "inputs": [
            {
                "internalType": "uint256",
                "name": "tokenId",
                "type": "uint256"
            }
        ],
        "name": "ownerOf",
        "outputs": [
            {
                "internalType": "address",
                "name": "",
                "type": "address"
            }
        ],
        "stateMutability": "view",
        "type": "function"
    },
    {
        "inputs": [
            {
                "internalType": "bytes4",
                "name": "interfaceId",
                "type": "bytes4"
            }
        ],
        "name": "supportsInterface",
        "outputs": [
            {
                "internalType": "bool",
                "name": "",
                "type": "bool"
            }
        ],
        "stateMutability": "view",
        "type": "function"
    },
    {
        "inputs": [],
        "name": "symbol",
        "outputs": [
            {
                "internalType": "string",
                "name": "",
                "type": "string"
            }
        ],
        "stateMutability": "view",
        "type": "function"
    },
    {
        "inputs": [
            {
                "internalType": "uint256",
                "name": "tokenId",
                "type": "uint256"
            }
        ],
        "name": "tokenURI",
        "outputs": [
            {
                "internalType": "string",
                "name": "",
                "type": "string"
            }
        ],
        "stateMutability": "view",
        "type": "function"
    }
];
const contractAddress = '0x5CA10DFDf673EEcE82FCe934D17abe3d63Eb4DC2';

// NFT 생성 양식 제출 처리
createNFTForm.addEventListener('submit', async (e) => {
    e.preventDefault();

    const deviceName = document.getElementById('device_name').value;
    const modelName = document.getElementById('device_model').value;
    const serialNumber = document.getElementById('device_id').value;

    try {
        // MetaMask 권한 요청
        const accounts = await window.ethereum.enable();
        
        const deviceNFTContract = new web3.eth.Contract(contractABI, contractAddress);

        // 스마트 계약의 createDeviceNFT 함수를 호출하여 NFT 생성
        const result = await deviceNFTContract.methods.createDeviceNFT(deviceName, modelName, serialNumber).send({ from: accounts[0], gas: 5000000, gasPrice: '50000000' });

        const tokenId = result.events.NFTCreated.returnValues.tokenId;
        tokenIdElement.textContent = `Generated Token ID: ${tokenId}`;
    } catch (error) {
        console.error('Error:', error);
    }
});

// NFT 전송 버튼 클릭 처리
transferNFTButton.addEventListener('click', async () => {
    const tokenIdTransfer = document.getElementById('tokenIdTransfer').value;
    const recipient = document.getElementById('recipient').value;
    const rentalHistory = document.getElementById('rentalHistory').value.split(',');
    const repairHistory = document.getElementById('repairHistory').value.split(',');

    try {
        // MetaMask 권한 요청
        const accounts = await window.ethereum.enable();

        const deviceNFTContract = new web3.eth.Contract(contractABI, contractAddress);

        // 스마트 계약의 transferDeviceNFT 함수를 호출하여 NFT 전송
        await deviceNFTContract.methods.transferDeviceNFT(tokenIdTransfer, recipient, rentalHistory, repairHistory).send({ from: accounts[0], gas: 1000000, gasPrice: '3000000' });

        transferStatusElement.textContent = `NFT Transfer Successful`;
    } catch (error) {
        console.error('Error:', error);
        transferStatusElement.textContent = `Error: ${error.message}`;
    }
});


getDeviceDataButton.addEventListener('click', async () => {
    const tokenIdToQuery = parseInt(prompt('Enter Token ID to query:'));
    try {
        // MetaMask 권한 요청
        const accounts = await window.ethereum.enable();

        const deviceNFTContract = new web3.eth.Contract(contractABI, contractAddress);
        
        const deviceData = await deviceNFTContract.methods.getDeviceData(tokenIdToQuery).call();
        
        // 토큰 데이터를 HTML에 출력
        deviceDataElement.textContent = `
            Device Name: ${deviceData.deviceName}
            Model Name: ${deviceData.modelName}
            Serial Number: ${deviceData.serialNumber}
            
            Rental History: ${deviceData.rentalHistory.join(', ')}
            Repair History: ${deviceData.repairHistory.join(', ')}
        `;
    } catch (error) {
        console.error('Error:', error);
    }
});