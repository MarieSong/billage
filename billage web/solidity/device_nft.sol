/*
// SPDX-License-Identifier: MIT
pragma solidity ^0.8.0;

import "@openzeppelin/contracts/token/ERC721/ERC721.sol";
import "@openzeppelin/contracts/utils/Counters.sol";

contract DeviceNFT is ERC721 {
    using Counters for Counters.Counter;
    Counters.Counter private _tokenIds;

    struct TokenData {
        string deviceName;
        string modelName;
        string serialNumber;
    }

    mapping(uint256 => TokenData) private _tokenData;
    mapping(uint256 => string[]) private _historicalRentalHistory;
    mapping(uint256 => string[]) private _historicalRepairHistory;

    event NFTCreated(uint256 indexed tokenId, address indexed creator);
    event NFTTransferred(uint256 indexed tokenId, address indexed from, address indexed to);
    event RentalHistoryRecorded(uint256 indexed tokenId, string rentalEvent);
    event RepairHistoryRecorded(uint256 indexed tokenId, string repairEvent);

    constructor() ERC721("DeviceNFT", "DNFT") {}

    function createDeviceNFT(
        string memory deviceName,
        string memory modelName,
        string memory serialNumber
    ) external returns (uint256) {
        _tokenIds.increment();
        uint256 newTokenId = _tokenIds.current();

        _mint(msg.sender, newTokenId);

        TokenData storage token = _tokenData[newTokenId];
        token.deviceName = deviceName;
        token.modelName = modelName;
        token.serialNumber = serialNumber;

        emit NFTCreated(newTokenId, msg.sender);

        return newTokenId;
    }

    function transferDeviceNFT(
        uint256 tokenId,
        address recipient,
        string[] memory rentalHistory,
        string[] memory repairHistory
    ) external {
        require(_isApprovedOrOwner(msg.sender, tokenId), "You are not the owner or approved to transfer this token.");

        _transfer(msg.sender, recipient, tokenId);

        TokenData storage token = _tokenData[tokenId];

        if (rentalHistory.length > 0) {
            for (uint256 i = 0; i < rentalHistory.length; i++) {
                _historicalRentalHistory[tokenId].push(rentalHistory[i]);
                emit RentalHistoryRecorded(tokenId, rentalHistory[i]);
            }
        }

        if (repairHistory.length > 0) {
            for (uint256 i = 0; i < repairHistory.length; i++) {
                _historicalRepairHistory[tokenId].push(repairHistory[i]);
                emit RepairHistoryRecorded(tokenId, repairHistory[i]);
            }
        }

        emit NFTTransferred(tokenId, msg.sender, recipient);
    }

    function getDeviceData(uint256 tokenId) external view returns (TokenData memory) {
        return _tokenData[tokenId];
    }

    function getRentalHistory(uint256 tokenId) external view returns (string[] memory) {
        return _historicalRentalHistory[tokenId];
    }

    function getRepairHistory(uint256 tokenId) external view returns (string[] memory) {
        return _historicalRepairHistory[tokenId];
    }

    function getTokenIdByIndex(uint256 index) external view returns (uint256) {
        require(index < _tokenIds.current(), "Index out of range");
        return index + 1; // Token IDs start from 1
    }
}
*/