# Billage
블록체인으로 사용자의 거래 이력을 저장하고, 기기별로 사용 이력을 안전하게 보관하는 전자기기 대여 서비스

# 기본 기능
### 애플리케이션 : 사용자용

<div>1. LoginActivity : login 상태에서 접근 가능</div>
<div>1-1 SignupActivity  : 회원가입</div>
<div>2. MainActivity</div>
<div>2-1 HomeFragment : 전체 기기 목록 조회</div>
<div>2-1-1 GoodsinfoActivity : 해당 기기에 대한 세부 정보 열람 가능 </div>
<div>2-2 RentFragment : 대여할 기기 종류, 대여 기간 입력 시 해당 기간에 대여가 가능한 기기 목록 조회 가능 </div>
<div>2-2-1 CheckRentActivity : 사용자 입력 정보 기반으로 대여 가능 기기 목록 조회</div>
<div>2-3 MoreFragment : 로그아웃, 연체 정보 확인 등 세부 기능 </div>
<div>2-3-1 RentDetailActivity : 세부 대여 내역 조회 </div>

### 웹 : 관리자용

<div> 새 기기 등록</div>
<div> 신규 기기 예약 등록</div>
<div> 예약 이력 확인 가능</div>
<div> - '수령확인' 버튼 클릭 시 메타마스크 활성화,다른 계정으로 해당 기기의 토큰을 보내고, 예약 정보를 데이터 베이스와 블록체인에 저장</div>
<div> 수리 내역 등록</div>
<div> - 해당 기록은 기기 상세 정보 화면에서 확인 가능</div>
<div> 기기상세 정보 화면 : 기기 정보, 대여 이력, 수리 이력 확인</div>
<div> - '불러오기' 버튼 : 해당 토큰에 저장된 대여 기록과 수리 기록 불러와서 현재의 데이터베이스와 비교하는 동기화 작업 수행</div>

