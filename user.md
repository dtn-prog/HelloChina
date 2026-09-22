 **ĐẶC TẢ YÊU CẦU PHẦN MỀM (SRS)**  
**HỆ THỐNG ỨNG DỤNG HELLOCHINA**

*Software Requirements Specification • Phân Hệ Quản Trị & Nghiệp Vụ Chức Năng*  
Phiên bản: 1.7 • Ngày cập nhật: 14/09/2026 • Đơn vị: TINOTECH Business Analysis Team

| 📌 MỤC ĐÍCH TÀI LIỆU SRSTài liệu này quy định chi tiết các đặc tả yêu cầu phần mềm (SRS) cho từng chức năng của ứng dụng HelloChina, bao gồm: Luồng nghiệp vụ chính, Luồng rẽ nhánh & ngoại lệ, Ràng buộc kiểm tra dữ liệu (Validation Rules) và Quy tắc nghiệp vụ hệ thống (Business Rules). Cấu trúc Heading phân cấp được thiết kế chuẩn để dễ dàng bổ sung các chức năng tiếp theo. |
| :---- |

# **PHÂN HỆ I. XÁC THỰC & QUẢN LÝ TÀI KHOẢN (AUTHENTICATION & ACCOUNT)**

## **1\. Chức năng Đăng ký tài khoản (AUTH-01: User Sign Up)**

### **1.1. Thông tin chung**

| Mã chức năng | AUTH-01 |
| :---- | :---- |
| **Tác nhân (Actor)** | Khách vãng lai (Guest) |
| **Mục tiêu** | Tạo mới tài khoản học viên trên hệ thống HelloChina để lưu trữ lộ trình học tập |
| **Tiền điều kiện (Pre-condition)** | Người dùng chưa đăng nhập, đang mở màn hình Đăng ký |
| **Hậu điều kiện (Post-condition)** | Tài khoản được khởi tạo thành công trong DB, sẵn sàng đăng nhập |

### **1.2. Luồng chính (Main Flow)**

| Bước | Tác nhân | Hành động |
| :---- | :---- | :---- |
| **1** | Người dùng | Nhập: Tên đăng nhập, Email, SDT, Mật khẩu, Xác nhận mật khẩu. |
| **2** | Người dùng | Tích chọn ô: \[x\] Đồng ý với Điều khoản & Chính sách. |
| **3** | Người dùng | Bấm nút "ĐĂNG KÝ". |
| **4** | Hệ thống | Kiểm tra tính hợp lệ của dữ liệu (theo các VR và BR). |
| **5** | Hệ thống | Tạo tài khoản mới, hiển thị thông báo thành công và chuyển hướng màn hình. |

### **1.3. Luồng rẽ nhánh & Ngoại lệ (Alternative & Exception Flows)**

| Mã luồng | Bước | Tác nhân | Điều kiện & Hành động xử lý |
| :---- | :---- | :---- | :---- |
| **AF-01** | Tại Bước 4 | Hệ thống | Dữ liệu không hợp lệ (Vi phạm VR01 \- VR04):• Hiển thị thông báo lỗi tương ứng dưới từng ô nhập liệu.• Use case kết thúc. |
| **AF-02** | Tại Bước 4 | Hệ thống | Chưa tích chọn Checkbox (Vi phạm VR05):• Hiển thị thông báo: "Vui lòng đồng ý với điều khoản".• Use case kết thúc. |
| **AF-03** | Tại Bước 4 | Hệ thống | Trùng Tên đăng nhập hoặc Email (Vi phạm BR01):• Hiển thị thông báo: "Tên đăng nhập hoặc Email đã tồn tại".• Use case kết thúc. |
| **AF-04** | Tại Bước 5 | Hệ thống | Lỗi kết nối mạng / Lỗi máy chủ:• Hiển thị thông báo: "Không thể kết nối. Vui lòng thử lại sau".• Use case kết thúc. |

### **1.4. Ràng buộc dữ liệu (Validation Rules \- VR)**

| Mã VR | Trường dữ liệu | Ràng buộc kiểm tra | Thông báo lỗi hiển thị |
| :---- | :---- | :---- | :---- |
| **VR01** | Tên đăng nhập | • Bắt buộc (Not null)• Độ dài 4 \- 20 ký tự• Chỉ gồm a-z, A-Z, 0-9, dấu \_• Không chứa khoảng trắng, ký tự đặc biệt | "Tên đăng nhập từ 4-20 ký tự, không chứa ký tự đặc biệt" |
| **VR02** | Email | • Bắt buộc• Đúng định dạng chuẩn user@domain.ext | "Email không đúng định dạng" |
| **VR03** | Mật khẩu | • Bắt buộc• Độ dài 8 \- 32 ký tự• Chứa ít nhất 1 chữ hoa và 1 chữ số• Ẩn mặc định •••••• (có nút 👁️ bật/tắt) | "Mật khẩu 8-32 ký tự, gồm ít nhất 1 chữ hoa và 1 số" |
| **VR04** | Xác nhận mật khẩu | • Bắt buộc• Trùng khớp 100% với trường Mật khẩu | "Mật khẩu xác nhận không trùng khớp" |
| **VR05** | Checkbox điều khoản | • Bắt buộc trạng thái \= True (Đã tích chọn) | "Vui lòng đồng ý với điều khoản" |

### **1.5. Quy tắc nghiệp vụ (Business Rules \- BR)**

| Mã BR | Tên quy tắc | Nội dung chi tiết |
| :---- | :---- | :---- |
| **BR01** | Tính duy nhất (Unique) | Mỗi Email và mỗi Tên đăng nhập chỉ được liên kết với 1 tài khoản duy nhất trong toàn hệ thống. |
| **BR02** | Bảo mật mật khẩu | Mật khẩu bắt buộc phải được mã hóa 1 chiều (Hash & Salt như bcrypt/Argon2) trước khi lưu vào DB. Tuyệt đối không lưu plain text. |
| **BR03** | Khởi tạo trạng thái | Tài khoản mới tạo mặc định có Role \= Student, Status \= Active. |
| **BR04** | Chống Spam Click | Khóa nút Đăng ký ngay sau cú click đầu tiên và kích hoạt hiệu ứng Loading để chống gửi request liên tiếp. |

## **2\. Chức năng Đăng nhập (AUTH-02: User Sign In)**

### **2.1. Thông tin chung**

| Mã chức năng | AUTH-02 |
| :---- | :---- |
| **Tác nhân (Actor)** | Người học (Learner) |
| **Mục tiêu** | Xác thực danh tính người dùng và cấp quyền truy cập vào ứng dụng HelloChina |
| **Tiền điều kiện (Pre-condition)** | Người dùng đã có tài khoản, đang ở màn hình Đăng nhập |
| **Hậu điều kiện (Post-condition)** | Đăng nhập thành công và chuyển vào màn hình chính |

### **2.2. Luồng chính (Main Flow)**

| Bước | Tác nhân | Hành động |
| :---- | :---- | :---- |
| **1** | Người dùng | Nhập: Email và Mật khẩu. |
| **2** | Người dùng | Bấm nút "ĐĂNG NHẬP". |
| **3** | Hệ thống | Kiểm tra tính hợp lệ của dữ liệu (theo VR) và xác thực thông tin đăng nhập (theo BR). |
| **4** | Hệ thống | Hiển thị thông báo đăng nhập thành công và chuyển hướng đến màn hình Trang chủ (Home). |

### **2.3. Luồng rẽ nhánh & Ngoại lệ (Alternative & Exception Flows)**

| Mã luồng | Bước | Tác nhân | Điều kiện & Hành động xử lý |
| :---- | :---- | :---- | :---- |
| **AF-01** | Tại Bước 3 | Hệ thống | Bỏ trống trường thông tin (Vi phạm VR01, VR02):• Hiển thị thông báo lỗi: "Vui lòng nhập đầy đủ thông tin".• Use case kết thúc. |
| **AF-02** | Tại Bước 3 | Hệ thống | Email không tồn tại:• Hiển thị thông báo: "Tài khoản không tồn tại trên hệ thống".• Use case kết thúc. |
| **AF-03** | Tại Bước 3 | Hệ thống | Mật khẩu không chính xác (Vi phạm BR01):• Hiển thị thông báo: "Mật khẩu không chính xác. Vui lòng thử lại".• Use case kết thúc. |
| **AF-04** | Tại Bước 3 | Hệ thống | Tài khoản bị vô hiệu hóa (banned) / bị khóa bởi quản trị viên (Vi phạm BR02):• Hiển thị thông báo: "Tài khoản của bạn đã bị khóa. Vui lòng liên hệ hỗ trợ".• Use case kết thúc. |
| **AF-05** | Tại Bước 4 | Hệ thống | Lỗi kết nối mạng / Lỗi máy chủ:• Hiển thị thông báo: "Không thể kết nối. Vui lòng thử lại sau".• Use case kết thúc. |
| AF-06 | Tại Bước 3 | Hệ thống | Nhập sai mật khẩu quá 5 lần liên tiếp (Vi phạm BR04): • Hệ thống tạm khóa đăng nhập trong 15 phút (không phải khóa tài khoản). • Hiển thị thông báo: "Bạn đã nhập sai mật khẩu quá 5 lần. Vui lòng thử lại sau 15 phút". • Use case kết thúc. |

### **2.4. Ràng buộc dữ liệu (Validation Rules \- VR)**

| Mã VR | Trường dữ liệu | Ràng buộc kiểm tra | Thông báo lỗi hiển thị |
| :---- | :---- | :---- | :---- |
| **VR01** | Email | • Bắt buộc nhập (Not null)• Đúng định dạng email (user@domain.ext) | "Vui lòng nhập đúng định dạng Email" |
| **VR02** | Mật khẩu | • Bắt buộc nhập (Not null)• Ẩn mặc định •••••• (có nút 👁️ bật/tắt hiển thị) | "Vui lòng nhập mật khẩu" |

### **2.5. Quy tắc nghiệp vụ (Business Rules \- BR)**

| Mã BR | Tên quy tắc | Nội dung chi tiết |
| :---- | :---- | :---- |
| **BR01** | Xác thực mật khẩu | So khớp mật khẩu người dùng vừa nhập với mật khẩu đã lưu trong hệ thống. |
| **BR02** | Trạng thái tài khoản | Chỉ cho phép đăng nhập nếu tài khoản đang hoạt động bình thường (Active). Nếu tài khoản đang bị khóa thì từ chối đăng nhập. |
| **BR03** | Tự động ghi nhớ đăng nhập | Sau khi đăng nhập thành công, hệ thống tự động ghi nhớ tài khoản trên thiết bị để người dùng không phải đăng nhập lại ở các lần mở app tiếp theo. |
| **BR04** | Giới hạn số lần thử | Nếu nhập sai mật khẩu quá 5 lần liên tiếp, hệ thống tạm khóa đăng nhập trong 15 phút để bảo vệ an toàn cho tài khoản. |

# **PHÂN HỆ II. TRANG CHỦ & ĐỘNG LỰC HỌC TẬP (HOME & GAMIFICATION)**

## **1\. Chức năng Xem chuỗi ngày học liên tục (HOME-01: View Streak)**

### **1.1. Thông tin chung**

| Mã chức năng | HOME-01 |
| :---- | :---- |
| **Tác nhân (Actor)** | Người học (Learner) |
| **Mục tiêu** | Hiển thị số ngày học liên tục (Streak) của người dùng |
| **Tiền điều kiện (Pre-condition)** | Người học đã đăng nhập, đang mở ứng dụng |
| **Hậu điều kiện (Post-condition)** | Số ngày Streak được hiển thị trên màn hình Trang chủ (Home) |

### **1.2. Luồng chính (Main Flow)**

| Bước | Tác nhân | Hành động |
| :---- | :---- | :---- |
| **1** | Người dùng | Truy cập vào màn hình Trang chủ (Home). |
| **2** | Hệ thống | Lấy dữ liệu số ngày streak của người dùng từ hệ thống. |
| **3** | Hệ thống | Hiện số ngày streak trên header. |
| **4** | Người dùng | Xem số ngày streak. |
| **5** | \- | Use case kết thúc. |

### **1.3. Luồng rẽ nhánh & Ngoại lệ (Alternative & Exception Flows)**

| Mã luồng | Bước | Tác nhân | Điều kiện & Hành động xử lý |
| :---- | :---- | :---- | :---- |
| **AF-01** | Tại Bước 2 | Hệ thống | Không lấy được dữ liệu streak (Mất mạng / Lỗi máy chủ):• Hiển thị thông báo: "Không thể tải dữ liệu streak".• Use case kết thúc. |

### **1.4. Ràng buộc dữ liệu (Validation Rules \- VR)**

| Mã VR | Trường dữ liệu | Ràng buộc kiểm tra | Thông báo lỗi hiển thị |
| :---- | :---- | :---- | :---- |
| **VR01** | Số ngày streak | • Là số nguyên không âm (\>= 0\)• Giá trị khởi tạo mặc định bằng 0 | "Dữ liệu số ngày không hợp lệ" |

### **1.5. Quy tắc nghiệp vụ (Business Rules \- BR)**

| Mã BR | Tên quy tắc | Nội dung chi tiết |
| :---- | :---- | :---- |
| **BR01** | Chu kỳ tính ngày | Một ngày học được tính từ 00:00 đến 23:59 theo múi giờ địa phương của người dùng. |
| **BR02** | Tăng chuỗi ngày streak (+1) | Người dùng hoàn thành ít nhất 1 bài học trong ngày (daily goal) thì số ngày streak được cộng thêm 1\. |
| **BR03** | Reset chuỗi ngày streak (về 0\) | Không hoàn thành bài học trong ngày và không có Khiên bảo vệ Streak (Streak Freeze) thì chuỗi ngày về 0\. |
| **BR04** | Bảo lưu streak bằng Khiên bảo vệ Streak (Streak Freeze) | Có Khiên bảo vệ Streak thì hệ thống tự động dùng để giữ nguyên chuỗi ngày khi bỏ lỡ 1 ngày học. |
| BR05 | Phân biệt Khiên bảo vệ Streak và Khôi phục Streak | Khiên bảo vệ Streak: tự động bảo toàn chuỗi khi bỏ lỡ 1 ngày. Khôi phục Streak (Streak Restore): dùng Gems lấy lại chuỗi đã mất. |

## **2\. Chức năng Xem số lượng Đá quý (HOME-02: View Gems)**

### **2.1. Thông tin chung**

| Mã chức năng | HOME-02 |
| :---- | :---- |
| **Tác nhân (Actor)** | Người học (Learner) |
| **Mục tiêu** | Hiển thị số dư tiền tệ Đá quý (Gems) hiện có của tài khoản |
| **Tiền điều kiện (Pre-condition)** | Người học đã đăng nhập, đang mở ứng dụng |
| **Hậu điều kiện (Post-condition)** | Số lượng Gems được hiển thị trên màn hình Trang chủ (Home) |

### **2.2. Luồng chính (Main Flow)**

| Bước | Tác nhân | Hành động |
| :---- | :---- | :---- |
| **1** | Người dùng | Truy cập vào màn hình Trang chủ (Home). |
| **2** | Hệ thống | Lấy dữ liệu số dư Gems của người dùng từ hệ thống. |
| **3** | Hệ thống | Hiện số lượng Gems trên header. |
| **4** | Người dùng | Xem số lượng Gems. |
| **5** | \- | Use case kết thúc. |

### **2.3. Luồng rẽ nhánh & Ngoại lệ (Alternative & Exception Flows)**

| Mã luồng | Bước | Tác nhân | Điều kiện & Hành động xử lý |
| :---- | :---- | :---- | :---- |
| **AF-01** | Tại Bước 2 | Hệ thống | Không lấy được dữ liệu Gems (Mất mạng / Lỗi máy chủ):• Hiển thị thông báo: "Không thể tải dữ liệu Gems".• Use case kết thúc. |

### **2.4. Ràng buộc dữ liệu (Validation Rules \- VR)**

| Mã VR | Trường dữ liệu | Ràng buộc kiểm tra | Thông báo lỗi hiển thị |
| :---- | :---- | :---- | :---- |
| **VR01** | Số lượng Gems | • Là số nguyên không âm (\>= 0\)• Giá trị khởi tạo mặc định bằng 0• Định dạng số hàng nghìn có dấu phẩy (VD: 1,250) | "Dữ liệu Gems không hợp lệ" |

### **2.5. Quy tắc nghiệp vụ (Business Rules \- BR)**

| Mã BR | Tên quy tắc | Nội dung chi tiết |
| :---- | :---- | :---- |
| **BR01** | Bản chất tiền tệ | Gems là đơn vị tiền tệ chính trong ứng dụng, được gắn liền với từng tài khoản người dùng và không thể chuyển nhượng giữa các tài khoản. |
| **BR02** | Cơ chế tích lũy (Cộng Gems) | Cộng Gems khi: hoàn thành bài học, Daily Quest, Streak, Achievement, Challenge, thắng 1vs1, lên hạng League, nhận thưởng từ rương, xem quảng cáo thưởng. |
| **BR03** | Cơ chế tiêu thụ (Trừ Gems) | Trừ Gems khi: nạp Energy, mua Khiên bảo vệ Streak, Double XP, tiêu Gems mở rương, Vé thi đấu, Khôi phục Streak, trang trí Profile, mở Mini Game. |
| **BR04** | Kiểm tra số dư khi giao dịch | Khi người dùng tiêu thụ Gems, hệ thống phải kiểm tra Số dư Gems \>= Giá vật phẩm. Nếu không đủ Gems, hệ thống từ chối giao dịch và hiển thị thông báo thiếu Gems. |
| **BR05** | Cập nhật số dư tức thời | Số dư Gems trên header phải được tự động cập nhật ngay lập tức (real-time) sau mỗi lần phát sinh giao dịch cộng hoặc trừ Gems. |

## **3\. Chức năng Xem năng lượng học tập (HOME-03: View Energy)**

### **3.1. Thông tin chung**

| Mã chức năng | HOME-03 |
| :---- | :---- |
| **Tác nhân (Actor)** | Người học (Learner) |
| **Mục tiêu** | Hiển thị mức năng lượng học tập (Energy) hiện tại của tài khoản |
| **Tiền điều kiện (Pre-condition)** | Người học đã đăng nhập, đang mở ứng dụng |
| **Hậu điều kiện (Post-condition)** | Mức Energy được hiển thị trên màn hình Trang chủ (Home) |

### **3.2. Luồng chính (Main Flow)**

| Bước | Tác nhân | Hành động |
| :---- | :---- | :---- |
| **1** | Người dùng | Truy cập vào màn hình Trang chủ (Home). |
| **2** | Hệ thống | Lấy dữ liệu năng lượng Energy của người dùng từ hệ thống. |
| **3** | Hệ thống | Hiện số lượng Energy trên header. |
| **4** | Người dùng | Xem số lượng Energy. |
| **5** | \- | Use case kết thúc. |

### **3.3. Luồng rẽ nhánh & Ngoại lệ (Alternative & Exception Flows)**

| Mã luồng | Bước | Tác nhân | Điều kiện & Hành động xử lý |
| :---- | :---- | :---- | :---- |
| **AF-01** | Tại Bước 2 | Hệ thống | Không lấy được dữ liệu Energy (Mất mạng / Lỗi máy chủ):• Hiển thị thông báo: "Không thể tải dữ liệu Energy".• Use case kết thúc. |

### **3.4. Ràng buộc dữ liệu (Validation Rules \- VR)**

| Mã VR | Trường dữ liệu | Ràng buộc dữ liệu | Thông báo hiển thị |
| :---- | :---- | :---- | :---- |
| **VR01** | Chỉ số Energy (Tài khoản Free) | • Là số nguyên trong khoảng từ 0 đến 100• Định dạng hiển thị: {Energy\_hiện\_tại} / {Energy\_tối\_đa} (VD: 85/100) | "Dữ liệu năng lượng không hợp lệ" |
| **VR02** | Chỉ số Energy (Tài khoản Premium) | • Định dạng hiển thị: Không giới hạn (hoặc ký hiệu vô cực ∞) | \- |

### **3.5. Quy tắc nghiệp vụ (Business Rules \- BR)**

| Mã BR | Tên quy tắc | Nội dung chi tiết |
| :---- | :---- | :---- |
| **BR01** | Giới hạn dung lượng | Tài khoản miễn phí (Free) có mức Energy tối đa mặc định là 100/100. Tài khoản trả phí (Premium) sở hữu năng lượng không giới hạn. |
| **BR02** | Cơ chế tiêu hao khi học | Energy bị trừ khi bắt đầu bài học: Bài ngắn \-10, Bài trung bình \-15, Bài khó \-20, Luyện phát âm \-10, AI hội thoại \-20, Thi thử HSK \-30. |
| **BR03** | Không trừ Energy khi làm sai | Hệ thống tuyệt đối không trừ Energy khi người dùng trả lời sai câu hỏi, đảm bảo người học thoải mái làm bài mà không bị áp lực mất năng lượng. |
| **BR04** | Cơ chế hồi phục Energy | Chỉ tài khoản Free: Energy hồi dần theo thời gian (+1 mỗi khoảng quy định, tối đa 100\) hoặc nạp đầy tức thì bằng Gems, xem quảng cáo, nâng cấp Premium. |
| **BR05** | Điều kiện chặn vào bài học | Chỉ tài khoản Free: Energy \= 0 hoặc không đủ mức tiêu hao tối thiểu thì thông báo hết năng lượng, hướng dẫn chờ hồi phục hoặc nạp thêm. |

# **PHÂN HỆ III. HỌC TẬP & BÀI GIẢNG (LEARNING & CURRICULUM)**

## **1\. Chức năng Xem lộ trình học tập (LEARN-01: View Learning Roadmap)**

### **1.1. Thông tin chung**

| Mã chức năng | LEARN-01 |
| :---- | :---- |
| **Tên chức năng** | Xem lộ trình học tập (View Learning Roadmap) |
| **Tác nhân (Actor)** | Người học (Learner) |
| **Mục tiêu** | Hiển thị lộ trình 4 giai đoạn, các chủ đề, bài học, tiến độ bài nhỏ và cấp bậc thành thạo (Level 1–3, Legendary) |
| **Tiền điều kiện (Pre-condition)** | Người học đã đăng nhập vào hệ thống |
| **Hậu điều kiện (Post-condition)** | Hiển thị lộ trình và tiến độ học mới nhất của người dùng |

### **1.2. Luồng chính (Main Flow)**

| Bước | Tác nhân | Hành động |
| :---- | :---- | :---- |
| **1** | Người dùng | Yêu cầu xem lộ trình học tập. |
| **2** | Hệ thống | Truy xuất dữ liệu: Giai đoạn, Chủ đề, Bài học, tiến độ Bài học nhỏ, Cấp độ bài học, điểm XP và mức Energy. |
| **3** | Hệ thống | Hiển thị lộ trình và tự động cuộn đến bài học hiện tại. |
| **4** | Người dùng | Chọn một bài học trên lộ trình. |
| **5** | Hệ thống | Hiển thị tóm tắt: Tên bài, cấp độ, số bài nhỏ đã hoàn thành, điểm XP, Energy và nút thao tác (Học bài / Ôn tập / Huyền thoại). |
| **6** | Người dùng | Chọn bắt đầu học (Học bài / Ôn tập / Thử thách Huyền thoại). |
| **7** | Hệ thống | Kiểm tra Energy và điều kiện mở khóa, sau đó mở phiên học tương ứng. |
| **8** | \- | Use case kết thúc. |

### **1.3. Luồng rẽ nhánh & Ngoại lệ (Alternative & Exception Flows)**

| Mã luồng | Bước | Tác nhân | Điều kiện & Hành động xử lý |
| :---- | :---- | :---- | :---- |
| **AF-01** | Bước 2 | Hệ thống | Lỗi kết nối / Máy chủ: Thông báo tải dữ liệu thất bại và cho phép thử lại. |
| **AF-02** | Bước 5 | Người dùng | Chọn bài học đang khóa: Thông báo cần hoàn thành các bài học trước. |
| **AF-03** | Bước 7 | Hệ thống | Không đủ Energy: Thông báo không đủ năng lượng và gợi ý nạp Energy. |

### **1.4. Ràng buộc dữ liệu (Validation Rules \- VR)**

| Mã VR | Trường dữ liệu | Ràng buộc kiểm tra | Thông báo lỗi hiển thị |
| :---- | :---- | :---- | :---- |
| **VR01** | Cấu trúc 4 cấp | Lộ trình gồm: Giai đoạn ➔ Chủ đề ➔ Bài học ➔ Bài học nhỏ. | "Dữ liệu lộ trình không hợp lệ" |
| **VR02** | Bài học nhỏ | Mỗi bài học chứa \>= 1 bài nhỏ có ID, nội dung và điểm XP. | "Dữ liệu bài nhỏ không hợp lệ" |
| **VR03** | Cấp độ thành thạo bài học (Lesson Mastery) | Thuộc một trong 4 mức: Level 1, Level 2, Level 3, Legendary. | "Cấp độ bài học không hợp lệ" |
| **VR04** | Điểm XP thưởng của bài học | Là số nguyên dương (\> 0). | "Thông số XP không hợp lệ" |
| VR05 | Energy sau khi hoàn thành bài | Là số nguyên không âm (\>= 0). | "Thông số Energy không hợp lệ" |

### **1.5. Quy tắc nghiệp vụ (Business Rules \- BR)**

| Mã BR | Tên quy tắc | Nội dung chi tiết |
| :---- | :---- | :---- |
| **BR01** | Cấu trúc phân cấp 4 tầng | • Tầng 1 (Giai đoạn): 4 giai đoạn nối tiếp (Nền tảng, Cơ bản, Giao tiếp, HSK).• Tầng 2 (Chủ đề): Nhóm bài học theo chủ đề (Thanh mẫu, Vận mẫu...).• Tầng 3 (Bài học): Các bài học thuộc chủ đề.• Tầng 4 (Bài học nhỏ): Các bài con tạo nên bài học. |
| **BR02** | Tính tiến độ bài học | Tiến độ bài học \= (Số bài nhỏ đã hoàn thành / Tổng số bài nhỏ) \* 100%. |
| **BR03** | 4 Cấp độ nâng cấp bài học | • Level 1: Hoàn thành 100% các bài học nhỏ lần đầu.• Level 2: Hoàn thành lượt ôn tập lần 1\.• Level 3: Hoàn thành lượt ôn tập lần 2\.• Legendary (Huyền thoại): Vượt qua bài Thử thách Huyền thoại.  \*Đặc điểm Legendary:\* Câu hỏi khó hơn, không có gợi ý, thưởng XP cao hơn, ghi nhận danh hiệu Huyền thoại. |
| **BR04** | Mở khóa tuần tự | • Hoàn thành bài nhỏ N mới mở khóa bài nhỏ N+1.• Đạt Level 1 bài học hiện tại mới mở khóa bài học tiếp theo.• Hoàn thành toàn bộ bài học trong chủ đề/giai đoạn mới mở khóa phần tiếp theo. |
| **BR05** | Tự động định vị | Tự động cuộn đến bài học đang học khi mở Lộ trình. |
| **BR06** | Trừ Energy & Nhận XP | Trừ Energy khi bắt đầu phiên học; cộng XP khi hoàn thành phiên học đó. |

## **2\. Chức năng Chọn đáp án câu hỏi (LRN-02: Select Quiz Answer)**

### **2.1. Thông tin chung**

| Mã chức năng | LRN-02 |
| :---- | :---- |
| **Tác nhân (Actor)** | Người học (Learner) |
| **Mục tiêu** | Kiểm tra và phản hồi kết quả chọn đáp án câu hỏi trắc nghiệm |
| **Tiền điều kiện (Pre-condition)** | Người dùng đang trong bài học và có câu hỏi trắc nghiệm hiển thị |
| **Hậu điều kiện (Post-condition)** | Ghi nhận kết quả làm bài, cập nhật tiến độ và cho phép chuyển câu tiếp theo |

### **2.2. Luồng chính (Main Flow)**

| Bước | Tác nhân | Hành động |
| :---- | :---- | :---- |
| **1** | Người dùng | Đọc câu hỏi và chọn 1 đáp án. |
| **2** | Người dùng | Nhấn nút kiểm tra đáp án. |
| **3** | Hệ thống | Kiểm tra đáp án đã chọn. |
| **4** | Hệ thống | Xác nhận đáp án đúng, hiển thị thông báo chúc mừng và cập nhật tiến độ bài học. |
| **5** | Người dùng | Nhấn nút tiếp tục. |
| **6** | Hệ thống | Chuyển sang câu hỏi tiếp theo. |
| **7** | \- | Use case kết thúc. |

### **2.3. Luồng rẽ nhánh & Ngoại lệ (Alternative & Exception Flows)**

| Mã luồng | Bước | Tác nhân | Điều kiện & Hành động xử lý |
| :---- | :---- | :---- | :---- |
| **AF-01** | Tại Bước 3 | Hệ thống | Chọn đáp án sai:• Ghi nhận kết quả sai, hiển thị đáp án chính xác của câu hỏi.• Người dùng nhấn tiếp tục, hệ thống chuyển sang câu hỏi tiếp theo (lưu câu sai vào Ngân hàng câu cần ôn tập).• Use case kết thúc. |
| **AF-02** | Tại Bước 2 | Hệ thống | Chưa chọn đáp án:• Nút kiểm tra ở trạng thái vô hiệu hóa (Disabled) cho đến khi người dùng chọn đáp án.• Quay lại Bước 1\. |

### **2.4. Ràng buộc dữ liệu (Validation Rules \- VR)**

| Mã VR | Trường dữ liệu | Ràng buộc kiểm tra | Thông báo lỗi hiển thị |
| :---- | :---- | :---- | :---- |
| **VR01** | Phương án chọn | • Bắt buộc chọn duy nhất 1 đáp án trước khi kiểm tra | "Vui lòng chọn đáp án" |
| **VR02** | Năng lượng (Energy) | • Năng lượng tối thiểu phải đủ theo mức tiêu hao của từng loại bài học trước khi bắt đầu | "Không đủ năng lượng để tham gia bài học" |

### **2.5. Quy tắc nghiệp vụ (Business Rules \- BR)**

| Mã BR | Tên quy tắc | Nội dung chi tiết |
| :---- | :---- | :---- |
| **BR01** | Ghi nhận kết quả & Tiến độ | Ghi nhận kết quả từng câu để cập nhật tiến độ. XP chỉ được cộng khi hoàn thành toàn bộ bài học (phiên Luyện tập dùng quy tắc XP riêng). |
| **BR02** | Quy tắc tiêu hao Năng lượng (Energy) | Năng lượng được trừ một lần theo từng phiên hoạt động khi bắt đầu bài học (không trừ theo từng câu trả lời sai):• Bài ngắn: \-10 ⚡• Bài trung bình: \-15 ⚡• Bài khó: \-20 ⚡• Luyện phát âm: \-10 ⚡• AI hội thoại: \-20 ⚡• Thi thử HSK: \-30 ⚡ |

## **3\. Chức năng Chọn Pinyin chuẩn (QUIZ-PINYIN: Pinyin Recognition)**

### **3.1. Thông tin chung**

| Mã chức năng | QUIZ-PINYIN |
| :---- | :---- |
| **Tác nhân (Actor)** | Người học (Learner) |
| **Mục tiêu** | Giúp người học nhận diện và chọn chính xác phiên âm Pinyin của chữ Hán trong bài học |
| **Tiền điều kiện (Pre-condition)** | Người dùng đang trong bài học, màn hình hiển thị thẻ chữ Hán câu hỏi và 4 phương án Pinyin |
| **Hậu điều kiện (Post-condition)** | Hệ thống xác nhận kết quả đúng/sai, cập nhật tiến độ bài học và điều hướng sang câu tiếp theo |

### **3.2. Luồng chính (Main Flow)**

| Bước | Tác nhân | Hành động |
| :---- | :---- | :---- |
| **1** | Người dùng | Đọc chữ Hán trên câu hỏi (nghe phát âm mẫu nếu cần). |
| **2** | Người dùng | Chọn 1 phương án Pinyin trong 4 đáp án (hệ thống ghi nhận lựa chọn và kích hoạt nút kiểm tra). |
| **3** | Người dùng | Nhấn nút "Kiểm tra kết quả". |
| **4** | Hệ thống | Kiểm tra và đối chiếu phương án đã chọn với đáp án chuẩn. |
| **5** | Hệ thống | Xác nhận đáp án đúng: Đánh dấu kết quả chính xác, hiển thị thông báo chúc mừng và cập nhật tiến độ bài học. |
| **6** | Người dùng | Nhấn nút "Tiếp tục". |
| **7** | Hệ thống | Chuyển sang câu hỏi tiếp theo trong bài học. |
| **8** | \- | Use case kết thúc. |

### **3.3. Luồng rẽ nhánh & Ngoại lệ (Alternative & Exception Flows)**

| Mã luồng | Bước | Tác nhân | Điều kiện & Hành động xử lý |
| :---- | :---- | :---- | :---- |
| **AF-01** | Tại Bước 4 | Hệ thống | Chọn sai Pinyin:• Đánh dấu phương án đã chọn không chính xác, hiển thị đáp án đúng để người dùng đối chiếu.• Người dùng nhấn xác nhận để tiếp tục câu sau (câu sai được lưu vào Ngân hàng câu cần ôn tập).• Use case kết thúc. |
| **AF-02** | Tại Bước 2 | Hệ thống | Chưa chọn phương án:• Nút kiểm tra ở trạng thái vô hiệu hóa (Disabled/Inactive).• Quay lại Bước 1\. |

### **3.4. Ràng buộc dữ liệu (Validation Rules \- VR)**

| Mã VR | Trường dữ liệu | Ràng buộc dữ liệu | Thông báo hiển thị |
| :---- | :---- | :---- | :---- |
| VR01 | Lựa chọn đáp án | Bắt buộc chọn duy nhất 1 trong 4 phương án Pinyin. | "Vui lòng chọn một đáp án Pinyin" |
| VR02 | Tệp âm thanh phát âm mẫu | • File .mp3, .m4a, .aac, .wav (dung lượng ≤ 5MB, thời lượng 2–15s).• Giọng chuẩn, rõ tiếng.• Người học được nghe lại không giới hạn số lần trong bài học. | "Không thể phát âm thanh" |
| VR03 | Danh sách 4 phương án Pinyin | • Bắt buộc có đúng 04 phương án Pinyin, không trùng lặp nội dung.• Mỗi phương án gắn với 01 chữ Hán của câu hỏi. | "Dữ liệu câu hỏi không hợp lệ" |

### **3.5. Quy tắc nghiệp vụ (Business Rules \- BR)**

| Mã BR | Tên quy tắc | Nội dung chi tiết |
| :---- | :---- | :---- |
| **BR01** | Trạng thái lựa chọn | Hệ thống quản lý 4 trạng thái nghiệp vụ của đáp án gồm: Chưa chọn (Default), Đang chọn (Selected), Trả lời đúng (Correct) và Trả lời sai (Incorrect). |
| **BR02** | Cơ chế tính điểm & Tiến độ | Mỗi câu trả lời đúng được ghi nhận hoàn thành và cộng tiến độ bài học. Hệ thống không trừ Energy khi làm sai câu hỏi. |
| **BR03** | Phát âm mẫu | Người học có thể nghe âm thanh phát âm mẫu chuẩn của từ vựng không giới hạn số lần. |

## **4\. Chức năng Dịch câu nhập liệu (QUIZ-TRANS: Sentence Translation Input)**

### **4.1. Thông tin chung**

| Mã chức năng | QUIZ-TRANS |
| :---- | :---- |
| **Tên chức năng** | Dịch câu nhập liệu (Sentence Translation Input) |
| **Tác nhân (Actor)** | Người học (Learner) |
| **Mục tiêu** | Đánh giá khả năng dịch nghĩa câu tiếng Trung sang tiếng Việt thông qua hình thức gõ văn bản |
| **Tiền điều kiện (Pre-condition)** | Người dùng đang trong bài học có câu hỏi dịch câu dạng gõ phím |
| **Hậu điều kiện (Post-condition)** | Hệ thống đối chiếu kết quả, cập nhật tiến độ bài học và chuyển câu tiếp theo (hoặc chuyển sang chế độ thẻ từ nếu người dùng yêu cầu) |

### **4.2. Luồng chính (Main Flow)**

| Bước | Tác nhân | Hành động |
| :---- | :---- | :---- |
| **1** | Người dùng | Xem câu tiếng Trung câu hỏi (có thể bấm nghe phát âm mẫu). |
| **2** | Người dùng | Nhập bản dịch tiếng Việt vào ô nhập liệu. |
| **3** | Người dùng | Bấm "Kiểm tra kết quả". |
| **4** | Hệ thống | Đối chiếu văn bản nhập với tập đáp án chuẩn ngữ nghĩa. |
| **5** | Hệ thống | Xác nhận đáp án đúng: Thông báo kết quả chính xác và cộng tiến độ bài học. |
| **6** | Người dùng | Bấm "Tiếp tục". |
| **7** | Hệ thống | Chuyển sang câu hỏi tiếp theo trong bài học. |
| **8** | \- | Use case kết thúc. |

### **4.3. Luồng rẽ nhánh & Ngoại lệ (Alternative & Exception Flows)**

| Mã luồng | Bước | Tác nhân | Điều kiện & Hành động xử lý |
| :---- | :---- | :---- | :---- |
| **AF-01** | Bước 4 | Hệ thống | Trả lời sai: Đánh dấu kết quả chưa chính xác, hiển thị bản dịch chuẩn và ghi nhận câu sai vào Ngân hàng câu cần ôn tập. Người dùng bấm tiếp tục để sang câu sau. |
| **AF-02** | Bước 2 | Hệ thống | Chưa nhập văn bản: Nút kiểm tra ở trạng thái vô hiệu hóa khi ô nhập liệu còn trống. |
| **AF-03** | Bước 2 | Người dùng | Chuyển sang chế độ thẻ từ (Card mode):• Người dùng bấm nút chuyển chế độ thẻ từ (Card).• Hệ thống bảo lưu câu hỏi hiện tại và chuyển đổi sang Use case 5\. Chức năng Sắp xếp câu tiếng Trung (QUIZ-REORDER).• Use case QUIZ-TRANS kết thúc. |

### **4.4. Ràng buộc dữ liệu (Validation Rules \- VR)**

| Mã VR | Trường dữ liệu | Ràng buộc dữ liệu | Thông báo hiển thị |
| :---- | :---- | :---- | :---- |
| VR01 | Ô nhập bản dịch | • Bắt buộc nhập bản dịch, không để trống.• Độ dài tối đa theo cấu hình câu hỏi. | "Vui lòng nhập bản dịch" |
| VR02 | Tệp âm thanh phát âm mẫu | • File .mp3, .m4a, .aac, .wav (dung lượng ≤ 5MB, thời lượng 2–15s).• Giọng chuẩn, rõ tiếng.• Người học được nghe lại không giới hạn số lần trong bài học. | "Không thể phát âm thanh" |
| VR03 | Tập đáp án chuẩn | • Bắt buộc có ít nhất 01 đáp án chuẩn cho câu hỏi.• Đối chiếu không phân biệt hoa/thường và dấu câu cuối (theo BR01). | "Dữ liệu câu hỏi không hợp lệ" |

### **4.5. Quy tắc nghiệp vụ (Business Rules \- BR)**

| Mã BR | Tên quy tắc | Nội dung chi tiết |
| :---- | :---- | :---- |
| **BR01** | Đối chiếu ngữ nghĩa linh hoạt | Chấp nhận nhiều cách dịch tương đương đúng ngữ cảnh (không phân biệt hoa/thường, dấu câu cuối). |
| **BR02** | Tính điểm & Tiến độ | Dịch đúng được tính hoàn thành câu và cộng tiến độ; dịch sai không bị trừ Energy. |
| **BR03** | Chuyển đổi phương thức làm bài linh hoạt | Cho phép người dùng chuyển đổi qua lại giữa chế độ gõ văn bản (QUIZ-TRANS) và chế độ chọn thẻ từ (QUIZ-REORDER) bất kỳ lúc nào trong quá trình làm câu hỏi. |

## **5\. Chức năng Sắp xếp câu tiếng Trung (QUIZ-REORDER: Sentence Reordering)**

### **5.1. Thông tin chung**

| Mã chức năng | QUIZ-REORDER |
| :---- | :---- |
| **Tác nhân (Actor)** | Người học (Learner) |
| **Mục tiêu** | Kiểm tra và rèn luyện kỹ năng ngữ pháp, trật tự từ trong câu tiếng Trung thông qua thao tác ghép nối các thẻ từ vựng rời rạc |
| **Tiền điều kiện (Pre-condition)** | Người dùng đang trong bài học, màn hình hiển thị câu gợi ý, vùng ghép câu và danh sách thẻ từ vựng |
| **Hậu điều kiện (Post-condition)** | Hệ thống xác nhận câu ghép đúng/sai, cập nhật tiến độ bài học và điều hướng sang câu tiếp theo |

### **5.2. Luồng chính (Main Flow)**

| Bước | Tác nhân | Hành động |
| :---- | :---- | :---- |
| **1** | Người dùng | Đọc câu gợi ý trên câu hỏi (có thể bấm biểu tượng loa để nghe phát âm mẫu nếu câu hỏi là tiếng Trung). |
| **2** | Người dùng | Lần lượt chạm chọn các thẻ từ vựng bên dưới để đưa vào vùng ghép câu theo đúng trật tự ngữ pháp (hệ thống kích hoạt nút kiểm tra khi đã ghép đủ từ). |
| **3** | Người dùng | Nhấn nút "Kiểm tra kết quả". |
| **4** | Hệ thống | Kiểm tra và đối chiếu trật tự chuỗi từ đã ghép với câu chuẩn ngữ pháp. |
| **5** | Hệ thống | Xác nhận đáp án đúng: Đánh dấu kết quả chính xác, hiển thị thông báo chúc mừng và cập nhật tiến độ bài học. |
| **6** | Người dùng | Nhấn nút "Tiếp tục". |
| **7** | Hệ thống | Chuyển sang câu hỏi tiếp theo trong bài học. |
| **8** | \- | Use case kết thúc. |

### **5.3. Luồng rẽ nhánh & Ngoại lệ (Alternative & Exception Flows)**

| Mã luồng | Bước | Tác nhân | Điều kiện & Hành động xử lý |
| :---- | :---- | :---- | :---- |
| **AF-01** | Tại Bước 4 | Hệ thống | Sắp xếp sai / Sai trật tự ngữ pháp:• Đánh dấu câu ghép chưa chính xác, hiển thị câu sắp xếp hoàn chỉnh chuẩn ngữ pháp để người dùng đối chiếu.• Người dùng nhấn xác nhận để tiếp tục câu sau (câu sai được lưu vào Ngân hàng câu cần ôn tập).• Use case kết thúc. |
| **AF-02** | Tại Bước 2 | Hệ thống | Chưa ghép đủ số lượng từ:• Nút kiểm tra ở trạng thái vô hiệu hóa (Disabled/Inactive) cho đến khi người dùng ghép đủ số lượng từ cần thiết.• Quay lại Bước 2\. |
| **AF-03** | Tại Bước 2 | Người dùng | Chuyển đổi sang chế độ gõ bàn phím:• Người dùng nhấn vào biểu tượng Nút bàn phím \[ ⌨️ \].• Hệ thống lưu trạng thái hiện tại và chuyển sang chức năng mã: QUIZ-TRANS.• Use case kết thúc. |

### **5.4. Ràng buộc dữ liệu (Validation Rules \- VR)**

| Mã VR | Trường dữ liệu | Ràng buộc kiểm tra | Thông báo lỗi hiển thị |
| :---- | :---- | :---- | :---- |
| **VR01** | Số lượng từ ghép | • Bắt buộc ghép đủ số lượng từ để tạo thành câu hoàn chỉnh trước khi nhấn kiểm tra | "Vui lòng hoàn thành ghép đủ câu" |
| **VR02** | Danh sách thẻ từ | • Gồm tập hợp các từ khóa tạo nên câu đúng và các từ gây nhiễu (nếu có theo cấu hình bài học) | "Dữ liệu thẻ từ không hợp lệ" |

### **5.5. Quy tắc nghiệp vụ (Business Rules \- BR)**

| Mã BR | Tên quy tắc | Nội dung chi tiết |
| :---- | :---- | :---- |
| **BR01** | Cơ chế ghép & Hoàn tác từ | Người dùng chạm vào thẻ từ ở danh sách để đưa lên vùng ghép câu; chạm vào thẻ từ đã ghép ở vùng câu để hoàn tác đưa trả lại danh sách lựa chọn. |
| **BR02** | Tính điểm & Tiến độ | Mỗi câu sắp xếp đúng được ghi nhận hoàn thành và cộng tiến độ bài học. Hệ thống không trừ Energy khi sắp xếp sai. |
| **BR03** | Cơ chế phát âm mẫu | • Đối với dạng câu hỏi là tiếng Trung: Trên khung câu hỏi luôn luôn có biểu tượng phát âm thanh để người học nghe phát âm mẫu câu hỏi không giới hạn số lần.• Đối với dạng câu hỏi là tiếng Việt (sắp xếp câu tiếng Trung): Sau khi hoàn thành câu hoặc xem phần giải thích kết quả, hệ thống cung cấp chức năng phát âm mẫu chuẩn của câu tiếng Trung hoàn chỉnh. |

## **6\. Chức năng Nhận diện thanh điệu (QUIZ-TONE: Tone Pitch Recognition)**

### **6.1. Thông tin chung**

| Mã chức năng | QUIZ-TONE |
| :---- | :---- |
| **Tác nhân (Actor)** | Người học (Learner) |
| **Mục tiêu** | Giúp người học luyện tai nghe và nhận diện chính xác 4 thanh điệu tiếng Trung thông qua âm thanh phát âm và sơ đồ cao độ |
| **Tiền điều kiện (Pre-condition)** | Người dùng đang trong bài học, màn hình hiển thị nút phát âm thanh âm tiết và 4 phương án thanh điệu |
| **Hậu điều kiện (Post-condition)** | Hệ thống xác nhận kết quả đúng/sai, cập nhật tiến độ bài học và điều hướng sang câu tiếp theo |

### **6.2. Luồng chính (Main Flow)**

| Bước | Tác nhân | Hành động |
| :---- | :---- | :---- |
| **1** | Người dùng | Nhấn nút phát âm thanh (Loa). |
| **2** | Hệ thống | Phát âm thanh chuẩn của âm tiết câu hỏi. |
| **3** | Người dùng | Chọn 1 phương án thanh điệu trong 4 đáp án (hệ thống ghi nhận lựa chọn và kích hoạt nút kiểm tra). |
| **4** | Người dùng | Nhấn nút "Kiểm tra đáp án". |
| **5** | Hệ thống | Kiểm tra và đối chiếu phương án đã chọn với thanh điệu chuẩn của âm thanh phát ra. |
| **6** | Hệ thống | Xác nhận đáp án đúng: Đánh dấu kết quả chính xác, hiển thị thông báo chúc mừng và cập nhật tiến độ bài học. |
| **7** | Người dùng | Nhấn nút "Tiếp tục". |
| **8** | Hệ thống | Chuyển sang câu hỏi tiếp theo trong bài học. |
| **9** | \- | Use case kết thúc. |

### **6.3. Luồng rẽ nhánh & Ngoại lệ (Alternative & Exception Flows)**

| Mã luồng | Bước | Tác nhân | Điều kiện & Hành động xử lý |
| :---- | :---- | :---- | :---- |
| **AF-01** | Tại Bước 5 | Hệ thống | Đáp án sai / Chọn nhầm thanh điệu:• Đánh dấu phương án đã chọn không chính xác, hiển thị thanh điệu đúng của âm để người dùng đối chiếu.• Người dùng nhấn xác nhận để tiếp tục câu sau (câu sai được lưu vào Ngân hàng câu cần ôn tập).• Use case kết thúc. |
| **AF-02** | Tại Bước 3 | Hệ thống | Chưa chọn phương án:• Nút kiểm tra ở trạng thái vô hiệu hóa (Disabled/Inactive).• Quay lại Bước 1 hoặc Bước 3\. |

### **6.4. Ràng buộc dữ liệu (Validation Rules \- VR)**

| Mã VR | Trường dữ liệu | Ràng buộc kiểm tra | Thông báo lỗi hiển thị |
| :---- | :---- | :---- | :---- |
| **VR01** | Số lượng chọn | • Bắt buộc chọn duy nhất 1 phương án thanh điệu trước khi kiểm tra | "Vui lòng chọn 1 đáp án thanh điệu" |
| **VR02** | Danh mục thanh điệu | • 4 phương án lựa chọn phải tương ứng với 4 thanh điệu chuẩn của tiếng Trung (Thanh 1, Thanh 2, Thanh 3, Thanh 4\) | "Dữ liệu thanh điệu không hợp lệ" |

### **6.5. Quy tắc nghiệp vụ (Business Rules \- BR)**

| Mã BR | Tên quy tắc | Nội dung chi tiết |
| :---- | :---- | :---- |
| **BR01** | Trạng thái lựa chọn | Hệ thống quản lý 4 trạng thái nghiệp vụ của đáp án gồm: Chưa chọn (Default), Đang chọn (Selected), Trả lời đúng (Correct) và Trả lời sai (Incorrect). |
| **BR02** | Nghe phát âm không giới hạn | Người học có thể nhấn nghe lại âm thanh phát âm của âm tiết nhiều lần mà không bị giới hạn trước khi bấm kiểm tra. |
| **BR03** | Tính điểm & Tiến độ | Mỗi câu nhận diện đúng thanh điệu được ghi nhận hoàn thành và cộng tiến độ bài học. Hệ thống không trừ Energy khi làm sai câu hỏi. |

## **7\. Chức năng Nghe sắp xếp câu (QUIZ-LISTEN-REORDER: Listening Sentence Reordering)**

### **7.1. Thông tin chung**

| Mã chức năng | QUIZ-LISTEN-REORDER |
| :---- | :---- |
| **Tác nhân (Actor)** | Người học (Learner) |
| **Mục tiêu** | Nghe câu tiếng Trung và sắp xếp các thẻ từ tiếng Việt thành câu dịch đúng |
| **Tiền điều kiện (Pre-condition)** | Có tệp âm thanh tiếng Trung và danh sách thẻ từ tiếng Việt |
| **Hậu điều kiện (Post-condition)** | Xác nhận đúng/sai, cộng tiến độ học và chuyển câu tiếp theo |

### **7.2. Luồng chính (Main Flow)**

| Bước | Tác nhân | Hành động |
| :---- | :---- | :---- |
| **1** | Người dùng | Bấm Loa để nghe câu phát âm tiếng Trung. |
| **2** | Người dùng | Chọn các thẻ từ tiếng Việt đưa lên vùng ghép câu. |
| **3** | Người dùng | Bấm "Kiểm tra" (khi đã ghép đủ từ). |
| **4** | Hệ thống | Đối chiếu câu ghép với đáp án chuẩn. |
| **5** | Hệ thống | Đúng: Báo thành công, phát âm thanh và cộng tiến độ. |
| **6** | Người dùng | Bấm "Tiếp tục" để sang câu tiếp theo. |
| **7** | \- | Use case kết thúc. |

### **7.3. Luồng rẽ nhánh & Ngoại lệ (Alternative & Exception Flows)**

| Mã luồng | Bước | Tác nhân | Điều kiện & Hành động xử lý |
| :---- | :---- | :---- | :---- |
| **AF-01** | Tại Bước 4 | Hệ thống | Sắp xếp sai: Báo lỗi, hiển thị câu dịch đúng và phiên âm tiếng Trung chuẩn \-\> Lưu vào Ngân hàng câu cần ôn tập. Use case kết thúc. |
| **AF-02** | Tại Bước 2 | Hệ thống | Chưa ghép đủ từ: Vô hiệu hóa nút Kiểm tra \-\> Yêu cầu ghép đủ từ. Quay lại Bước 2\. |
| **AF-03** | Tại Bước 2 | Người dùng | Bấm nút bàn phím \[ ⌨️ \]: Chuyển sang chức năng mã QUIZ-TRANS-VOICE (gõ text). Use case kết thúc. |

### **7.4. Ràng buộc dữ liệu (Validation Rules \- VR)**

| Mã VR | Trường dữ liệu | Ràng buộc kiểm tra | Thông báo lỗi hiển thị |
| :---- | :---- | :---- | :---- |
| **VR01** | Tệp âm thanh | • Chuẩn .mp3, .m4a, .aac, .wav; giọng Bắc Kinh; tải dưới 1.0s | "Lỗi tải âm thanh" |
| **VR02** | Thẻ từ ghép | • Bắt buộc ghép đủ số lượng từ trước khi kiểm tra | "Vui lòng ghép đủ câu" |
| **VR03** | Danh sách thẻ | • Gồm thẻ từ đúng và thẻ từ gây nhiễu | "Dữ liệu thẻ không hợp lệ" |

### **7.5. Quy tắc nghiệp vụ (Business Rules \- BR)**

| Mã BR | Tên quy tắc | Nội dung chi tiết |
| :---- | :---- | :---- |
| **BR01** | Chỉ dùng Voice tiếng Trung | Đề bài CHỈ SỬ DỤNG DUY NHẤT VOICE TIẾNG TRUNG, không hiển thị chữ Hán hay Pinyin trước khi gửi đáp án. |
| **BR02** | Nghe lại không giới hạn | Cho phép bấm Loa nghe lại nhiều lần mà không bị trừ điểm hay năng lượng. |
| **BR03** | Cơ chế ghép & Hoàn tác | Chạm thẻ ở danh sách để đưa lên câu; chạm thẻ trên câu để gỡ trả lại danh sách. |

## **8\. Chức năng Nghe và gõ lại văn bản (QUIZ-TRANS-VOICE: Listening Text Input Quiz)**

### **8.1. Thông tin chung**

| Mã chức năng | QUIZ-TRANS-VOICE |
| :---- | :---- |
| **Tên chức năng** | Nghe và gõ lại văn bản (Listening Text Input Quiz) |
| **Tác nhân (Actor)** | Người học (Learner) |
| **Mục tiêu** | Đánh giá kỹ năng nghe hiểu tiếng Trung thông qua việc nghe âm thanh và gõ lại nội dung văn bản chính xác |
| **Tiền điều kiện (Pre-condition)** | Người dùng đang trong bài học có câu hỏi nghe gõ văn bản; tệp âm thanh câu hỏi hợp lệ |
| **Hậu điều kiện (Post-condition)** | Ghi nhận kết quả trả lời, cập nhật tiến độ bài học và chuyển sang câu tiếp theo (hoặc chuyển sang chế độ sắp xếp thẻ từ theo yêu cầu) |

### **8.2. Luồng chính (Main Flow)**

| Bước | Tác nhân | Hành động |
| :---- | :---- | :---- |
| **1** | Người dùng | Yêu cầu phát âm thanh câu tiếng Trung. |
| **2** | Hệ thống | Phát tệp âm thanh câu tiếng Trung theo yêu cầu. |
| **3** | Người dùng | Nhập nội dung văn bản nghe được vào ô nhập liệu. |
| **4** | Người dùng | Bấm "Kiểm tra kết quả". |
| **5** | Hệ thống | Đối chiếu văn bản nhập liệu với đáp án chuẩn. |
| **6** | Hệ thống | Xác nhận đáp án đúng: Thông báo kết quả chính xác và cộng tiến độ bài học. |
| **7** | Người dùng | Bấm "Tiếp tục". |
| **8** | Hệ thống | Chuyển sang câu hỏi tiếp theo trong bài học. |
| **9** | \- | Use case kết thúc. |

### **8.3. Luồng rẽ nhánh & Ngoại lệ (Alternative & Exception Flows)**

| Mã luồng | Bước | Tác nhân | Điều kiện & Hành động xử lý |
| :---- | :---- | :---- | :---- |
| **AF-01** | Bước 3 | Người dùng | Chuyển sang chế độ sắp xếp thẻ từ (Card mode):• Người dùng bấm nút chuyển chế độ thẻ từ (Card).• Hệ thống bảo lưu câu hỏi hiện tại và chuyển đổi sang Use case 7\. Chức năng Nghe sắp xếp câu (QUIZ-LISTEN-REORDER).• Use case QUIZ-TRANS-VOICE kết thúc. |
| **AF-02** | Bước 5 | Hệ thống | Trả lời sai: Thông báo kết quả chưa chính xác, hiển thị đáp án đúng và lưu câu hỏi vào Ngân hàng câu cần ôn tập. Người dùng bấm tiếp tục để sang câu sau (không bị trừ Energy). |
| **AF-03** | Bước 3 | Hệ thống | Chưa nhập văn bản: Nút kiểm tra ở trạng thái vô hiệu hóa khi ô nhập liệu còn trống. |
| **EF-01** | Bước 1 | Hệ thống | Lỗi phát âm thanh: Không tải được tệp âm thanh ➔ Thông báo: "Không thể phát âm thanh. Vui lòng thử lại". |

### **8.4. Ràng buộc dữ liệu (Validation Rules \- VR)**

| Mã VR | Trường dữ liệu | Ràng buộc kiểm tra | Thông báo lỗi hiển thị |
| :---- | :---- | :---- | :---- |
| **VR01** | Tệp âm thanh | Bắt buộc tệp âm thanh câu hỏi tồn tại và có thể phát được. | "Không tải được tệp âm thanh câu hỏi" |
| **VR02** | Văn bản nhập liệu | Không được để trống khi gửi kiểm tra; giới hạn tối đa 200 ký tự. | "Vui lòng nhập câu trả lời trước khi kiểm tra" |
| **VR03** | Chuẩn hóa chuỗi | Tự động loại bỏ khoảng trắng thừa đầu/cuối chuỗi (Trim) trước khi so khớp. | \- |

### **8.5. Quy tắc nghiệp vụ (Business Rules \- BR)**

| Mã BR | Tên quy tắc | Nội dung chi tiết |
| :---- | :---- | :---- |
| **BR01** | Chuyển đổi linh hoạt với QUIZ-LISTEN-REORDER | Hệ thống hỗ trợ chuyển đổi linh hoạt qua lại giữa phương thức gõ văn bản (QUIZ-TRANS-VOICE) và phương thức sắp xếp thẻ từ (QUIZ-LISTEN-REORDER) cho cùng một câu hỏi. |
| **BR02** | Bỏ qua khác biệt dấu câu | Tự động bỏ qua sự khác biệt về dấu câu cuối dòng khi đối chiếu kết quả. |
| **BR03** | Bảo lưu năng lượng & Tính tiến độ | Trả lời đúng: cộng tiến độ. Trả lời sai: không trừ điểm/Energy, lưu vào Ngân hàng câu cần ôn tập. |

## **9\. Chức năng Ghép cặp từ vựng (QUIZ-MATCH: Word Matching)**

### **9.1. Thông tin chung**

| Mã chức năng | QUIZ-MATCH |
| :---- | :---- |
| **Tác nhân (Actor)** | Người học (Learner) |
| **Mục tiêu** | Kiểm tra và củng cố phản xạ ghi nhớ từ vựng tiếng Trung và nghĩa tiếng Việt tương ứng thông qua thao tác ghép nối các cặp thẻ |
| **Tiền điều kiện (Pre-condition)** | Người dùng đang trong bài học, màn hình hiển thị 2 cột thẻ (Cột trái: Tiếng Trung; Cột phải: Nghĩa tiếng Việt) |
| **Hậu điều kiện (Post-condition)** | Người dùng ghép đúng tất cả các cặp thẻ, hệ thống xác nhận hoàn thành, cập nhật tiến độ bài học và điều hướng sang câu tiếp theo |

### **9.2. Luồng chính (Main Flow)**

| Bước | Tác nhân | Hành động |
| :---- | :---- | :---- |
| **1** | Người dùng | Quan sát các thẻ từ vựng phân bố ở 2 cột trên màn hình. |
| **2** | Người dùng | Chạm chọn 1 thẻ ở một cột (hệ thống ghi nhận trạng thái đang chọn), sau đó chạm chọn tiếp 1 thẻ tương ứng ở cột đối diện. |
| **3** | Hệ thống | Kiểm tra và đối chiếu cặp 2 thẻ vừa chọn. |
| **4** | Hệ thống | Xác nhận Ghép đúng: Đánh dấu 2 thẻ đã ghép đúng và chuyển sang trạng thái đã hoàn thành (vô hiệu hóa cặp thẻ đã ghép thành công). |
| **5** | Người dùng | Lặp lại thao tác ghép cho đến khi ghép xong toàn bộ các cặp thẻ còn lại. |
| **6** | Hệ thống | Xác nhận hoàn thành tất cả các cặp: Hiển thị thông báo chúc mừng và cập nhật tiến độ bài học. |
| **7** | Người dùng | Nhấn nút "Tiếp tục". |
| **8** | Hệ thống | Chuyển sang câu hỏi tiếp theo trong bài học. |
| **9** | \- | Use case kết thúc. |

### **9.3. Luồng rẽ nhánh & Ngoại lệ (Alternative & Exception Flows)**

| Mã luồng | Bước | Tác nhân | Điều kiện & Hành động xử lý |
| :---- | :---- | :---- | :---- |
| **AF-01** | Tại Bước 3 | Hệ thống | Ghép sai cặp thẻ:• Đánh dấu cặp thẻ ghép không chính xác.• Hủy trạng thái đang chọn của cả 2 thẻ để người dùng thao tác ghép lại (hệ thống ghi nhận số lần ghép sai để ôn tập). |
| **AF-02** | Tại Bước 2 | Hệ thống | Thay đổi lựa chọn trong cùng 1 cột:• Nếu người dùng đã chọn 1 thẻ ở cột A nhưng chạm tiếp thẻ khác cũng ở cột A, hệ thống tự động chuyển trạng thái đang chọn sang thẻ mới. |

### **9.4. Ràng buộc dữ liệu (Validation Rules \- VR)**

| Mã VR | Trường dữ liệu | Ràng buộc kiểm tra | Thông báo lỗi hiển thị |
| :---- | :---- | :---- | :---- |
| **VR01** | Số lượng cặp thẻ | • Mỗi bài tập gồm từ 4 đến 6 cặp thẻ đối ứng (tổng cộng 8 đến 12 thẻ trên màn hình) | "Dữ liệu bài học không hợp lệ" |
| **VR02** | Tính duy nhất | • Mỗi thẻ ở cột này chỉ có duy nhất 1 thẻ tương ứng chính xác ở cột đối diện | \- |

### **9.5. Quy tắc nghiệp vụ (Business Rules \- BR)**

| Mã BR | Tên quy tắc | Nội dung chi tiết |
| :---- | :---- | :---- |
| **BR01** | Cơ chế ghép thẻ linh hoạt | Người dùng có thể chọn thẻ ở cột nào trước cũng được (chọn Cột tiếng Trung trước rồi Cột tiếng Việt sau, hoặc ngược lại). |
| **BR02** | Trạng thái cặp thẻ hoàn thành | Khi người dùng ghép đúng một cặp từ, hệ thống chuyển 2 thẻ sang trạng thái đã hoàn thành (không cho phép chọn lại) và giữ nguyên vị trí cho đến khi ghép xong toàn bộ bài tập. |
| **BR03** | Tính điểm & Tiến độ | Hoàn thành ghép đúng tất cả các cặp thẻ được ghi nhận hoàn thành bài tập và cộng tiến độ. Hệ thống không trừ Energy khi người dùng ghép sai cặp thẻ. |

## **10\. Chức năng Nghe và chọn đáp án (QUIZ-LISTEN-SELECT: Listening Single Choice Quiz)**

### **10.1. Thông tin chung**

| Mã chức năng | QUIZ-LISTEN-SELECT |
| :---- | :---- |
| **Tác nhân (Actor)** | Người học (Learner) |
| **Mục tiêu** | Kiểm tra khả năng nghe hiểu âm thanh tiếng Trung chuẩn và chọn đáp án chính xác tương ứng |
| **Tiền điều kiện (Pre-condition)** | Người dùng đang trong phiên học bài có câu hỏi dạng nghe chọn đáp án; thiết bị đã bật âm thanh |
| **Hậu điều kiện (Post-condition)** | Hệ thống ghi nhận kết quả và lưu tiến độ câu hỏi của người dùng |

### **10.2. Luồng chính (Main Flow)**

| Bước | Tác nhân | Hành động |
| :---- | :---- | :---- |
| 1 | Hệ thống | Hiển thị nút phát âm thanh, danh sách các đáp án lựa chọn và nút "Kiểm tra" ở trạng thái chưa kích hoạt. |
| 2 | Hệ thống | Tự động phát tệp âm thanh mẫu của câu hỏi. |
| 3 | Người dùng | Nghe âm thanh (hoặc bấm nút phát lại) và chọn 1 đáp án. |
| 4 | Hệ thống | Ghi nhận lựa chọn của người dùng và kích hoạt nút "Kiểm tra". |
| 5 | Người dùng | Bấm vào nút "Kiểm tra". |
| 6 | Hệ thống | Kiểm tra đáp án: Trùng khớp với đáp án đúng → Hiển thị thông báo trả lời chính xác kèm giải nghĩa và nút "Tiếp tục". |
| 7 | Người dùng | Bấm vào nút "Tiếp tục". |
| 8 | Hệ thống | Lưu tiến độ câu hỏi và chuyển sang câu hỏi tiếp theo. |
| 9 | \- | Use case kết thúc. |

### **10.3. Luồng rẽ nhánh & Ngoại lệ (Alternative & Exception Flows)**

| Mã luồng | Bước | Tác nhân | Điều kiện & Hành động xử lý |
| :---- | :---- | :---- | :---- |
| **AF-01** | Tại Bước 3 | Người dùng | Đổi đáp án trước khi kiểm tra:• Hệ thống bỏ chọn đáp án cũ và ghi nhận đáp án mới. |
| **AF-02** | Tại Bước 3 | Người dùng | Nghe lại âm thanh:• Hệ thống phát lại tệp âm thanh từ đầu (không giới hạn số lần, giữ nguyên đáp án đang chọn). |
| **EF-01** | Tại Bước 6 | Hệ thống | Trả lời SAI đáp án:• Hiển thị thông báo không chính xác kèm đáp án đúng và giải nghĩa chi tiết.• Chuyển nút bấm thành 'Đã hiểu' để người dùng bấm chuyển sang câu hỏi tiếp theo. |
| **EF-02** | Tại Bước 2 | Hệ thống | Không tải được tệp âm thanh (Mất mạng / Lỗi máy chủ):• Hiển thị thông báo: 'Không thể phát âm thanh. Vui lòng chạm để thử lại'. |

### **10.4. Ràng buộc dữ liệu (Validation Rules \- VR)**

| Mã VR | Trường dữ liệu | Ràng buộc kiểm tra | Thông báo lỗi hiển thị |
| :---- | :---- | :---- | :---- |
| **VR01** | Lựa chọn đáp án | Bắt buộc phải chọn đúng 1 đáp án mới được bấm kiểm tra | Nút Kiểm tra không hoạt động khi chưa chọn đáp án |
| **VR02** | Danh sách đáp án | Có từ 3 đến 4 phương án lựa chọn, không trùng lặp nội dung | Dữ liệu câu hỏi không hợp lệ |
| **VR03** | Tệp âm thanh (Audio) | • Tệp âm thanh bắt buộc tồn tại và đúng định dạng chuẩn (.mp3, .m4a, .wav)• Dung lượng tệp ≤ 5 MB, bitrate ≥ 128 kbps | Không thể phát tệp âm thanh do tệp bị lỗi hoặc sai định dạng |

### **10.5. Quy tắc nghiệp vụ (Business Rules \- BR)**

| Mã BR | Tên quy tắc | Nội dung chi tiết |
| :---- | :---- | :---- |
| **BR01** | Quy tắc tính điểm | • Trả lời đúng: Ghi nhận câu trả lời đúng và cộng điểm XP khi hoàn thành bài học.• Trả lời sai: Không cộng điểm câu này, không trừ điểm hay Energy của người dùng và chuyển sang câu hỏi tiếp theo. |
| **BR02** | Quy tắc xáo trộn vị trí đáp án | Tự động xáo trộn ngẫu nhiên vị trí các đáp án mỗi lần tạo bài học để tránh ghi nhớ vị trí cố định. |
| **BR03** | Khóa lựa chọn khi đã kiểm tra | Sau khi người dùng bấm "Kiểm tra", hệ thống khóa lựa chọn, không cho phép thay đổi đáp án. |

## **11\. Chức năng Luyện phát âm (QUIZ-VOICE-TRANS: Pronunciation Practice)**

### **11.1. Thông tin chung**

| Mã chức năng | QUIZ-VOICE-TRANS |
| :---- | :---- |
| **Tác nhân (Actor)** | Người học (Learner) |
| **Mục tiêu** | Cho phép người dùng thu âm giọng nói để luyện phát âm câu tiếng Trung theo yêu cầu của bài học |
| **Tiền điều kiện (Pre-condition)** | Người dùng đang trong bài học có câu hỏi luyện phát âm; thiết bị đã cấp quyền Micro |
| **Hậu điều kiện (Post-condition)** | Đoạn ghi âm hợp lệ được chuyển sang Use Case QUIZ-VOICE-RESULT để phân tích và đánh giá kết quả |

### **11.2. Luồng chính (Main Flow)**

| Bước | Tác nhân | Hành động |
| :---- | :---- | :---- |
| 1 | Người dùng | Đọc câu yêu cầu và bấm nút Micro để bắt đầu ghi âm. |
| 2 | Hệ thống | Bật thu âm từ Micro và hiển thị trạng thái đang ghi âm. |
| 3 | Người dùng | Nói câu tiếng Trung theo yêu cầu. |
| 4 | Người dùng | Bấm lại nút Micro để dừng ghi âm. |
| 5 | Hệ thống | Dừng thu âm, lưu tạm đoạn âm thanh và kích hoạt nút "Kiểm tra". |
| 6 | Người dùng | Bấm vào nút "Kiểm tra". |
| 7 | Hệ thống | Gửi đoạn âm thanh đã ghi và chuyển sang xử lý tại Use Case QUIZ-VOICE-RESULT (Đánh giá kết quả luyện phát âm). |
| 8 | \- | Use case kết thúc. |

### **11.3. Luồng rẽ nhánh & Ngoại lệ (Alternative & Exception Flows)**

| Mã luồng | Bước | Tác nhân | Điều kiện & Hành động xử lý |
| :---- | :---- | :---- | :---- |
| **AF-01** | Tại Bước 1 | Người dùng | Nghe âm thanh mẫu:• Người dùng bấm nút phát âm thanh để nghe giọng đọc chuẩn của câu yêu cầu (cho phép nghe lại nhiều lần). |
| **AF-02** | Tại Bước 5 | Người dùng | Ghi âm lại:• Người dùng bấm lại nút Micro để hủy bản ghi âm cũ và thu âm lại từ đầu trước khi bấm Kiểm tra. |
| **EF-01** | Tại Bước 1 | Hệ thống | Chưa cấp quyền truy cập Micro:• Thiết bị chưa cho phép truy cập Micro → Hiển thị thông báo: 'Vui lòng cấp quyền Micro để sử dụng tính năng luyện phát âm'. |
| **EF-02** | Tại Bước 5 | Hệ thống | Thời lượng ghi âm không hợp lệ (\< 0.5s hoặc \> 60s):• Bản thu dưới 0.5s hoặc vượt quá 60s → Hiển thị thông báo: 'Thời lượng ghi âm phải từ 0.5 giây đến 60 giây. Vui lòng thử lại'. |

### **11.4. Ràng buộc dữ liệu (Validation Rules \- VR)**

| Mã VR | Trường dữ liệu | Ràng buộc kiểm tra | Thông báo lỗi hiển thị |
| :---- | :---- | :---- | :---- |
| **VR01** | Đoạn ghi âm | Bắt buộc phải có đoạn ghi âm người dùng thực hiện mới được bấm kiểm tra | Nút Kiểm tra không hoạt động khi chưa có bản ghi âm |
| **VR02** | Thời lượng ghi âm | Thời lượng đoạn ghi âm bắt buộc từ 0.5 giây đến 60 giây (0.5s ≤ Thời lượng ≤ 60s) | Thời lượng ghi âm phải từ 0.5s đến 60s |
| **VR03** | Quyền thiết bị | Quyền sử dụng Micro (Microphone Permission) \= Đã cấp quyền (Granted) | Vui lòng cấp quyền Micro trong cài đặt thiết bị để tiếp tục |

### **11.5. Quy tắc nghiệp vụ (Business Rules \- BR)**

| Mã BR | Tên quy tắc | Nội dung chi tiết |
| :---- | :---- | :---- |
| **BR01** | Chuyển tiếp sang QUIZ-VOICE-RESULT | Sau khi người dùng bấm 'Kiểm tra' với bản ghi âm hợp lệ, hệ thống tự động chuyển dữ liệu âm thanh sang Use Case QUIZ-VOICE-RESULT để phân tích và hiển thị kết quả. |
| **BR02** | Khóa thao tác khi gửi dữ liệu | Trong thời gian hệ thống đang gửi dữ liệu sang QUIZ-VOICE-RESULT, khóa tạm thời nút Kiểm tra để tránh gửi yêu cầu trùng lặp. |
| **BR03** | Dừng âm thanh mẫu khi thu âm | Khi người dùng bắt đầu thu âm, hệ thống tự động dừng phát âm thanh mẫu (nếu đang phát) để tránh lẫn tạp âm vào bản ghi. |
| **BR04** | Ghi đè bản thu trong bộ nhớ tạm | Mỗi lần ghi âm lại, hệ thống tự động xóa bản thu cũ và chỉ lưu giữ bản ghi âm mới nhất để gửi sang bước kiểm tra. |
| **BR05** | Xử lý gián đoạn khi thu âm | Nếu bị gián đoạn (cuộc gọi đến, thoát app ra nền) trong lúc đang thu âm, hệ thống tự động hủy bản thu dở dang và hoàn nguyên nút Micro về trạng thái sẵn sàng ban đầu. |

## **12\. Chức năng Đánh giá kết quả luyện phát âm (QUIZ-VOICE-RESULT: Voice Quiz Result Assessment)**

### **12.1. Thông tin chung**

| Mã chức năng | QUIZ-VOICE-RESULT |
| :---- | :---- |
| **Tác nhân (Actor)** | Người học (Learner) |
| **Mục tiêu** | Tiếp nhận đoạn ghi âm từ QUIZ-VOICE-TRANS, phân tích và hiển thị điểm số chi tiết theo từng tiêu chí (Pinyin, Thanh điệu, Ngữ điệu, Phát âm, Lưu loát), tổng điểm chung và gợi ý các lỗi sai cần khắc phục |
| **Tiền điều kiện (Pre-condition)** | Hệ thống đã nhận được tệp âm thanh ghi âm hợp lệ từ Use Case QUIZ-VOICE-TRANS |
| **Hậu điều kiện (Post-condition)** | Hiển thị bảng kết quả đánh giá chi tiết, ghi nhận điểm số và lưu tiến độ học tập khi người dùng bấm tiếp tục |

### **12.2. Luồng chính (Main Flow)**

| Bước | Tác nhân | Hành động |
| :---- | :---- | :---- |
| 1 | Hệ thống | Tiếp nhận tệp ghi âm từ QUIZ-VOICE-TRANS và gửi đến mô-đun AI để chấm điểm. • Tài khoản Free: Nhận điểm tổng và nhận xét cơ bản. • Tài khoản Premium: Nhận phân tích âm vị chi tiết theo từng âm tiết (đặc quyền Premium theo PRACTICE-01 BR02). |
| 2 | Hệ thống | Tính toán điểm số theo 5 tiêu chí: Pinyin, Thanh điệu, Ngữ điệu, Phát âm, Lưu loát và Tổng điểm chung. |
| 3 | Hệ thống | Xác định tất cả các từ/ký tự chưa đạt điểm tuyệt đối (\< 100 điểm) và tổng hợp danh sách lỗi cần khắc phục. |
| 4 | Hệ thống | Hiển thị màn hình kết quả gồm: Trình phát lại bản ghi âm, Tổng điểm chung, điểm chi tiết 5 tiêu chí, khung gợi ý khắc phục lỗi các từ chưa đạt 100 điểm, nút "Chi tiết lỗi sai" và nút "Tiếp tục". |
| 5 | Người dùng | Xem chi tiết kết quả đánh giá (có thể bấm nghe lại bản thu của mình) và bấm vào nút "Tiếp tục". |
| 6 | Hệ thống | Lưu tiến độ câu hỏi và chuyển sang câu hỏi tiếp theo trong bài học. |
| 7 | \- | Use case kết thúc. |

### **12.3. Luồng rẽ nhánh & Ngoại lệ (Alternative & Exception Flows)**

| Mã luồng | Bước | Tác nhân | Điều kiện & Hành động xử lý |
| :---- | :---- | :---- | :---- |
| **AF-01** | Tại Bước 4 | Người dùng | Xem chi tiết từ bị lỗi:• Người dùng bấm vào từng từ bị đánh dấu chưa đạt 100 điểm → Hệ thống hiển thị chi tiết lỗi (sai thanh điệu, sai âm đầu hoặc âm cuối) và phát âm thanh mẫu của riêng từ đó. |
| **AF-02** | Tại Bước 4 | Hệ thống | Phát âm đạt điểm tuyệt đối (100 điểm):• Tất cả các từ đều đạt 100 điểm → Hệ thống ẩn danh sách lỗi cần khắc phục và hiển thị thông báo khen ngợi hoàn hảo. |
| **EF-01** | Tại Bước 1 | Hệ thống | Lỗi phân tích âm thanh (Mất mạng / Lỗi máy chủ AI):• Không nhận được kết quả phân tích từ AI → Hiển thị thông báo: 'Không thể phân tích âm thanh lúc này. Vui lòng thử lại' kèm nút Thử lại. |

### **12.4. Ràng buộc dữ liệu (Validation Rules \- VR)**

| Mã VR | Trường dữ liệu | Ràng buộc kiểm tra | Thông báo lỗi hiển thị |
| :---- | :---- | :---- | :---- |
| **VR01** | Dữ liệu đầu vào | Tệp âm thanh từ QUIZ-VOICE-TRANS bắt buộc tồn tại và không rỗng | Dữ liệu âm thanh đầu vào không hợp lệ |
| **VR02** | Thang điểm đánh giá | • Tổng điểm chung và điểm thành phần là số nguyên từ 0 đến 100 (0 ≤ Điểm ≤ 100\)• 5 tiêu chí bắt buộc gồm: Pinyin, Thanh điệu, Ngữ điệu, Phát âm, Lưu loát | Dữ liệu điểm số đánh giá không hợp lệ |
| **VR03** | Danh sách lỗi khắc phục | Hiển thị tất cả các từ/ký tự có điểm đánh giá dưới 100 điểm (\< 100 điểm) | Chỉ ẩn danh sách lỗi khi toàn bộ các từ đạt điểm tuyệt đối 100 điểm |

### **12.5. Quy tắc nghiệp vụ (Business Rules \- BR)**

| Mã BR | Tên quy tắc | Nội dung chi tiết |
| :---- | :---- | :---- |
| **BR01** | Quy tắc hiển thị lỗi khắc phục | • Nếu có từ có điểm thành phần \< 100 điểm: Hệ thống hiển thị chi tiết toàn bộ các từ cần khắc phục để người dùng nhận biết và chọn luyện lại hoặc tiếp tục (không trừ điểm/Energy).• Nếu tất cả các từ đều đạt 100 điểm tuyệt đối: Ẩn khung danh sách lỗi và hiển thị thông báo hoàn hảo. |
| **BR02** | Trọng số tính Tổng điểm chung | Trung bình có trọng số của 5 tiêu chí: Pinyin 30%, Thanh điệu 30%, Lưu loát 15%, Ngữ điệu 15%, Phát âm 10%. |

## **13\. Chức năng Luyện viết chữ Hán (QUIZ-WRITE-HANZI: Interactive Hanzi Stroke Writing)**

### **13.1. Thông tin chung**

| Mã chức năng | QUIZ-WRITE-HANZI |
| :---- | :---- |
| **Tác nhân (Actor)** | Người học (Learner) |
| **Mục tiêu** | Cho phép người dùng theo dõi số nét chữ Hán và thực hiện viết trên ô lưới cảm ứng theo nét mờ cho sẵn |
| **Tiền điều kiện (Pre-condition)** | Người dùng đang trong bài học có câu hỏi luyện viết chữ Hán |
| **Hậu điều kiện (Post-condition)** | Dữ liệu các nét vẽ hoàn chỉnh được chuyển sang Use Case QUIZ-WRITE-RESULT để phân tích và đánh giá kết quả |

### **13.2. Luồng chính (Main Flow)**

| Bước | Tác nhân | Hành động |
| :---- | :---- | :---- |
| 1 | Hệ thống | Hiển thị chữ Hán cần viết, Pinyin, nghĩa tiếng Việt, tổng số nét và ô lưới viết chữ có sẵn nét mờ hướng dẫn. |
| 2 | Người dùng | Quan sát số nét và dùng ngón tay (hoặc bút cảm ứng) viết theo các nét mờ trên ô lưới. |
| 3 | Hệ thống | Ghi nhận từng nét vẽ theo thời gian thực và kích hoạt nút "Kiểm tra" khi người dùng hoàn thành đủ các nét. |
| 4 | Người dùng | Bấm vào nút "Kiểm tra". |
| 5 | Hệ thống | Gửi dữ liệu các nét vẽ và chuyển sang xử lý tại Use Case QUIZ-WRITE-RESULT (Đánh giá kết quả luyện viết). |
| 6 | \- | Use case kết thúc. |

### **13.3. Luồng rẽ nhánh & Ngoại lệ (Alternative & Exception Flows)**

| Mã luồng | Bước | Tác nhân | Điều kiện & Hành động xử lý |
| :---- | :---- | :---- | :---- |
| **AF-01** | Tại Bước 2 | Người dùng | Bật / Tắt hiển thị nét mờ mẫu:• Người dùng bấm nút ẩn/hiện nét gợi ý → Hệ thống ẩn hoặc hiện lại các đường nét mờ trên ô lưới. |
| **AF-02** | Tại Bước 2 | Người dùng | Hoàn tác nét vừa vẽ (Undo):• Người dùng bấm nút Hoàn tác → Hệ thống xóa nét vẽ gần nhất vừa thực hiện. |
| **AF-03** | Tại Bước 2 | Người dùng | Xóa tất cả viết lại từ đầu (Clear All):• Người dùng bấm nút Xóa toàn bộ → Hệ thống xóa toàn bộ các nét đã vẽ trên ô lưới để người dùng viết lại từ nét đầu tiên. |

### **13.4. Ràng buộc dữ liệu (Validation Rules \- VR)**

| Mã VR | Trường dữ liệu | Ràng buộc dữ liệu | Thông báo hiển thị |
| :---- | :---- | :---- | :---- |
| VR01 | Ô lưới viết chữ Hán | • Bắt buộc có ít nhất 01 nét vẽ hợp lệ trên ô lưới.• Chỉ kích hoạt nút 'Kiểm tra' khi người dùng đã viết đủ tổng số nét của chữ Hán. | "Vui lòng viết đủ số nét của chữ Hán" |
| VR02 | Dữ liệu nét vẽ (Stroke) | • Mỗi nét được ghi nhận từ lúc chạm xuống đến khi nhấc tay/bút (theo BR02).• Tọa độ và thứ tự nét bắt buộc hợp lệ trước khi gửi sang QUIZ-WRITE-RESULT. | "Dữ liệu nét vẽ không hợp lệ" |
| VR03 | Chữ Hán mẫu | • Bắt buộc có Chữ Hán, Pinyin, nghĩa tiếng Việt và tổng số nét.• Bắt buộc có ảnh nét mờ hướng dẫn trên ô lưới. | "Dữ liệu chữ Hán mẫu không hợp lệ" |

### **13.5. Quy tắc nghiệp vụ (Business Rules \- BR)**

| Mã BR | Tên quy tắc | Nội dung chi tiết |
| :---- | :---- | :---- |
| **BR01** | Chuyển tiếp sang QUIZ-WRITE-RESULT | Sau khi người dùng bấm 'Kiểm tra' với đầy đủ nét vẽ, hệ thống chuyển toàn bộ tọa độ và thứ tự các nét sang Use Case QUIZ-WRITE-RESULT để chấm điểm độ chuẩn nét và độ cân đối. |
| **BR02** | Quy tắc nhận diện nét vẽ | Mỗi lần người dùng chạm xuống và nhấc tay/bút khỏi màn hình ô lưới được tính là kết thúc 1 nét (Stroke). |
| **BR03** | Khóa thao tác khi gửi dữ liệu | Trong thời gian hệ thống đang chuyển dữ liệu sang QUIZ-WRITE-RESULT, khóa tạm thời ô vẽ và nút Kiểm tra để tránh gửi yêu cầu trùng lặp. |

## **14\. Chức năng Đánh giá kết quả luyện viết chữ Hán (QUIZ-WRITE-RESULT: Hanzi Writing Result Assessment)**

### **14.1. Thông tin chung**

| Mã chức năng | QUIZ-WRITE-RESULT |
| :---- | :---- |
| **Tác nhân (Actor)** | Người học (Learner) |
| **Mục tiêu** | Tiếp nhận dữ liệu nét vẽ từ QUIZ-WRITE-HANZI, phân tích và hiển thị điểm số chi tiết theo 4 tiêu chí (Độ chính xác nét, Thứ tự nét, Độ cân đối, Nét thanh đậm), tổng điểm chung và nhận xét cải thiện của AI |
| **Tiền điều kiện (Pre-condition)** | Hệ thống đã nhận được dữ liệu nét vẽ hoàn chỉnh từ Use Case QUIZ-WRITE-HANZI |
| **Hậu điều kiện (Post-condition)** | Hiển thị bảng kết quả đánh giá chi tiết, ghi nhận điểm số và lưu tiến độ học tập khi người dùng bấm tiếp tục |

### **14.2. Luồng chính (Main Flow)**

| Bước | Tác nhân | Hành động |
| :---- | :---- | :---- |
| 1 | Hệ thống | Tiếp nhận dữ liệu các nét vẽ từ QUIZ-WRITE-HANZI và gửi đến mô-đun AI để phân tích nét chữ. |
| 2 | Hệ thống | Tính toán điểm số theo 4 tiêu chí: Độ chính xác nét vẽ, Thứ tự nét vẽ, Độ cân đối bố cục, Nét thanh đậm và Tổng điểm chung. |
| 3 | Hệ thống | Tạo nhận xét đánh giá của AI và đưa ra lời khuyên cải thiện nét viết cụ thể (nếu có nét chưa chuẩn). |
| 4 | Hệ thống | Hiển thị màn hình kết quả gồm: Hình ảnh chữ viết của người dùng (kèm các nét được đánh dấu), Tổng điểm chung, điểm chi tiết 4 tiêu chí, khung nhận xét của AI, nút "Chi tiết lỗi sai" và nút "Tiếp tục". |
| 5 | Người dùng | Xem chi tiết kết quả đánh giá và bấm vào nút "Tiếp tục". |
| 6 | Hệ thống | Lưu tiến độ câu hỏi và chuyển sang câu hỏi tiếp theo trong bài học. |
| 7 | \- | Use case kết thúc. |

### **14.3. Luồng rẽ nhánh & Ngoại lệ (Alternative & Exception Flows)**

| Mã luồng | Bước | Tác nhân | Điều kiện & Hành động xử lý |
| :---- | :---- | :---- | :---- |
| **AF-01** | Tại Bước 4 | Người dùng | Xem chi tiết nét bị sai hoặc lệch:• Người dùng chạm vào từng nét trên hình ảnh chữ viết của mình → Hệ thống highlight nét đó và hiển thị nhận xét chi tiết (sai hướng nét, sai thứ tự hoặc lệch tâm). |
| **AF-02** | Tại Bước 4 | Hệ thống | Chữ viết đạt điểm tuyệt đối (100 điểm):• Tất cả các tiêu chí đều đạt 100 điểm → Hệ thống hiển thị huy hiệu Xuất sắc kèm lời khen ngợi hoàn hảo. |
| **EF-01** | Tại Bước 1 | Hệ thống | Lỗi phân tích nét chữ (Mất mạng / Lỗi máy chủ AI):• Không nhận được kết quả từ AI → Hiển thị thông báo: 'Không thể phân tích nét chữ lúc này. Vui lòng thử lại' kèm nút Thử lại. |

### **14.4. Ràng buộc dữ liệu (Validation Rules \- VR)**

| Mã VR | Trường dữ liệu | Ràng buộc dữ liệu | Thông báo hiển thị |
| :---- | :---- | :---- | :---- |
| VR01 | Điểm tổng chung | • Là số nguyên trong khoảng từ 0 đến 100\.• Tính theo trọng số 4 tiêu chí: Độ chính xác nét vẽ (35%), Thứ tự nét vẽ (35%), Độ cân đối bố cục (20%), Nét thanh đậm (10%) (theo BR02). | "Dữ liệu điểm đánh giá không hợp lệ" |
| VR02 | Điểm chi tiết 4 tiêu chí | • Mỗi tiêu chí là số nguyên trong khoảng từ 0 đến 100\.• Bắt buộc hiển thị đủ 4 tiêu chí trên màn hình kết quả. | "Dữ liệu điểm đánh giá không hợp lệ" |
| VR03 | Dữ liệu nét vẽ đầu vào | • Bắt buộc nhận được dữ liệu nét vẽ hoàn chỉnh từ QUIZ-WRITE-HANZI.• Bắt buộc có kết quả phân tích từ mô-đun AI trước khi hiển thị kết quả. | "Không thể phân tích nét chữ lúc này. Vui lòng thử lại" |

### **14.5. Quy tắc nghiệp vụ (Business Rules \- BR)**

| Mã BR | Tên quy tắc | Nội dung chi tiết |
| :---- | :---- | :---- |
| **BR01** | Phân loại kết quả nét chữ | • Xuất sắc (= 100 điểm): cả 4 tiêu chí đạt tối đa, hiển thị huy hiệu Xuất sắc.• Đạt (≥ 70): ghi nhận hoàn thành câu hỏi và cộng XP khi hoàn thành bài học.• Cần cải thiện (\< 70): hiển thị nhận xét chi tiết để luyện lại hoặc tiếp tục (không trừ điểm/Energy). |
| **BR02** | Trọng số tính Tổng điểm chung | Tổng điểm chung là trung bình có trọng số của 4 tiêu chí: Độ chính xác nét vẽ (35%), Thứ tự nét vẽ (35%), Độ cân đối bố cục (20%), Nét thanh đậm (10%). |

## **15\. Chức năng Nghe và điền từ (QUIZ-LISTEN-FILL: Listening Fill in the Blank Quiz)**

### **15.1. Thông tin chung**

| Mã chức năng | QUIZ-LISTEN-FILL |
| :---- | :---- |
| **Tác nhân (Actor)** | Người học (Learner) |
| **Mục tiêu** | Luyện kỹ năng nghe hiểu và ngữ pháp bằng cách nghe câu phát âm tiếng Trung và điền từ/ký tự còn thiếu vào vị trí khuyết trong câu |
| **Tiền điều kiện (Pre-condition)** | Người dùng đang trong bài học có câu hỏi Nghe điền từ; tệp âm thanh tiếng Trung và dữ liệu câu khuyết hợp lệ |
| **Hậu điều kiện (Post-condition)** | Ghi nhận kết quả trả lời, cập nhật tiến độ bài học và chuyển sang câu tiếp theo khi người dùng bấm tiếp tục |

### **15.2. Luồng chính (Main Flow)**

| Bước | Tác nhân | Hành động |
| :---- | :---- | :---- |
| 1 | Hệ thống | Hiển thị màn hình câu hỏi gồm nút nghe âm thanh, câu văn bản có vị trí khuyết và khu vực điền từ. |
| 2 | Người dùng | Bấm vào nút nghe âm thanh. |
| 3 | Hệ thống | Phát âm thanh câu tiếng Trung. |
| 4 | Người dùng | Điền từ còn thiếu vào các vị trí khuyết trong câu. |
| 5 | Người dùng | Bấm vào nút "Kiểm tra". |
| 6 | Hệ thống | Đối chiếu từ đã điền với đáp án chuẩn. |
| 7 | Hệ thống | Xác nhận đáp án đúng: Thông báo kết quả đúng, phát âm thanh chúc mừng, cộng tiến độ bài học và kích hoạt nút "Tiếp tục". |
| 8 | Người dùng | Bấm vào nút "Tiếp tục". |
| 9 | Hệ thống | Chuyển sang câu hỏi tiếp theo trong bài học. |
| 10 | \- | Use case kết thúc. |

### **15.3. Luồng rẽ nhánh & Ngoại lệ (Alternative & Exception Flows)**

| Mã luồng | Bước | Tác nhân | Điều kiện & Hành động xử lý |
| :---- | :---- | :---- | :---- |
| **AF-01** | Tại Bước 4 | Người dùng | Xóa từ đã điền:• Người dùng chạm vào từ đã điền trong vị trí khuyết để xóa từ. |
| **AF-02** | Tại Bước 6 | Hệ thống | Trả lời sai (Điền từ sai):• Hệ thống thông báo kết quả chưa đúng, hiển thị đáp án đúng chuẩn xác và kích hoạt nút 'Tiếp tục' (không trừ điểm hay Energy). |
| **EF-01** | Tại Bước 2 | Hệ thống | Lỗi phát âm thanh:• Tệp âm thanh không tải được → Hệ thống hiển thị thông báo: 'Không thể phát âm thanh. Vui lòng thử lại'. |

### **15.4. Ràng buộc dữ liệu (Validation Rules \- VR)**

| Mã VR | Trường dữ liệu | Ràng buộc kiểm tra | Thông báo lỗi hiển thị |
| :---- | :---- | :---- | :---- |
| **VR01** | Dữ liệu âm thanh | Bắt buộc tệp âm thanh câu hỏi là tiếng Trung (Mandarin Chinese) chuẩn | Tệp âm thanh tiếng Trung không hợp lệ |
| **VR02** | Dữ liệu vị trí khuyết | Bắt buộc phải điền đầy đủ tất cả các vị trí khuyết mới cho phép bấm nút Kiểm tra | Nút Kiểm tra ở trạng thái vô hiệu hóa (Disabled) khi chưa điền đủ từ |
| **VR03** | Chuẩn hóa chuỗi so khớp | Tự động loại bỏ khoảng trắng thừa đầu/cuối và không phân biệt chữ hoa/thường đối với Pinyin | Áp dụng tự động trong thuật toán so khớp chuỗi |

### **15.5. Quy tắc nghiệp vụ (Business Rules \- BR)**

| Mã BR | Tên quy tắc | Nội dung chi tiết |
| :---- | :---- | :---- |
| **BR01** | Quy tắc ngôn ngữ giọng đọc | Giọng đọc phát âm trong câu hỏi bắt buộc 100% là tệp âm thanh tiếng Trung chuẩn bản xứ. |
| **BR02** | Phương thức điền từ linh hoạt | Tùy theo cấu hình bài học, hệ thống hỗ trợ phương thức bấm chọn từ thẻ gợi ý hoặc gõ trực tiếp từ bàn phím vào vị trí khuyết. |
| **BR03** | Quy tắc bảo lưu năng lượng | Trả lời sai không bị trừ Energy hay điểm tích lũy, hệ thống lưu nhận diện lỗi để hỗ trợ người dùng ôn tập lại. |

# **PHÂN HỆ IV. TRÍ TUỆ NHÂN TẠO AI & LUYỆN TẬP (AI & PRACTICE)**

## **1\. Chức năng Xem các danh mục Luyện tập (PRACTICE-01: View Practice Categories)**

### **1.1. Thông tin chung**

| Mã chức năng | PRACTICE-01 |
| :---- | :---- |
| **Tác nhân (Actor)** | Người học (Learner) |
| **Mục tiêu** | Hiển thị toàn bộ các tính năng luyện tập được phân theo 2 danh mục: Kỹ năng & Giao tiếp và Kiến thức & Củng cố |
| **Tiền điều kiện (Pre-condition)** | Người học đã đăng nhập, đang mở ứng dụng |
| **Hậu điều kiện (Post-condition)** | Màn hình Luyện tập hiển thị đầy đủ các danh mục và danh sách các mục luyện tập phù hợp với gói tài khoản |

### **1.2. Luồng chính (Main Flow)**

| Bước | Tác nhân | Hành động |
| :---- | :---- | :---- |
| **1** | Người dùng | Bấm vào tab "Luyện" (Luyện tập) trên thanh điều hướng (Bottom Navigation Bar). |
| **2** | Hệ thống | Lấy dữ liệu các mục luyện tập thuộc 2 danh mục: Kỹ năng & Giao tiếp và Kiến thức & Củng cố từ hệ thống. |
| **3** | Hệ thống | Hiển thị danh sách các mục luyện tập theo từng danh mục lên màn hình Luyện tập. |
| **4** | Người dùng | Xem các mục luyện tập. |
| **5** | \- | Use case kết thúc. |

### **1.3. Luồng rẽ nhánh & Ngoại lệ (Alternative & Exception Flows)**

| Mã luồng | Bước | Tác nhân | Điều kiện & Hành động xử lý |
| :---- | :---- | :---- | :---- |
| **AF-01** | Tại Bước 2 | Hệ thống | Không lấy được dữ liệu luyện tập (Mất mạng / Lỗi máy chủ):• Hiển thị thông báo: "Không thể tải danh mục luyện tập. Vui lòng thử lại".• Use case kết thúc. |

### **1.4. Ràng buộc dữ liệu (Validation Rules \- VR)**

| Mã VR | Trường dữ liệu | Ràng buộc kiểm tra | Thông báo lỗi hiển thị |
| :---- | :---- | :---- | :---- |
| **VR01** | Danh mục luyện tập | • Danh sách danh mục không được rỗng• Có đầy đủ thông tin tên danh mục và danh sách các mục luyện tập bên trong | "Dữ liệu danh mục luyện tập không hợp lệ" |
| **VR02** | Phân quyền gói dịch vụ | • Mỗi mục luyện tập được gắn cờ hiển thị phù hợp: Tiêu chuẩn (Free) hoặc Nâng cao (⭐ Premium) | "Dữ liệu gói dịch vụ không hợp lệ" |

### **1.5. Quy tắc nghiệp vụ (Business Rules \- BR)**

| Mã BR | Tên quy tắc | Nội dung chi tiết |
| :---- | :---- | :---- |
| **BR01** | Phân nhóm 2 danh mục chuẩn | Màn hình Luyện tập được phân chia thành 2 nhóm chuyên biệt:• Danh mục 1 (Kỹ năng & Giao tiếp): Luyện phát âm AI, Hội thoại AI nhập vai, Luyện nghe phản xạ, Luyện viết chữ Hán.• Danh mục 2 (Kiến thức & Củng cố): Ôn tập từ vựng, Sửa lỗi sai cá nhân, Ngữ pháp chuyên sâu, Luyện đề thi HSK. |
| **BR02** | Quy tắc hiển thị theo gói Free vs Premium | • Free: bài luyện tập tiêu chuẩn, HSK cơ bản; AI giới hạn lượt (theo AI-FREE-CHAT VR05); AI Pronunciation chỉ có điểm tổng và nhận xét cơ bản.• Premium (⭐): toàn bộ mục nâng cao, AI Pronunciation phân tích âm vị chi tiết, AI Conversation không giới hạn, Personalized Learning.• Ngoại lệ: Flashcard (FLASHCARD-01) mở tự do cho cả Free, không khóa theo cấp độ. |
| **BR03** | Đánh dấu tính năng Premium | Các mục luyện tập chuyên sâu độc quyền của Premium sẽ có biểu tượng vương miện/ngôi sao ⭐ Premium để người dùng Free nhận biết và bấm nâng cấp khi có nhu cầu. |

## **2\. Chức năng Hội thoại tự do với AI (AI-FREE-CHAT: Free AI Conversation Practice)**

### **2.1. Thông tin chung**

| Mã chức năng | AI-FREE-CHAT |
| :---- | :---- |
| **Tác nhân (Actor)** | Người học (Learner) |
| **Mục tiêu** | Cho phép người dùng thực hành giao tiếp tiếng Trung tự do với AI thông qua văn bản hoặc giọng nói theo ngữ cảnh thực tế |
| **Tiền điều kiện (Pre-condition)** | Người học đã đăng nhập và thiết bị có kết nối mạng Internet ổn định; cấp quyền Micro nếu sử dụng giọng nói |
| **Hậu điều kiện (Post-condition)** | Dữ liệu cuộc trò chuyện hoàn chỉnh được chuyển sang Use Case AI-CHAT-RESULT để phân tích và đánh giá kết quả |

### **2.2. Luồng chính (Main Flow)**

| Bước | Tác nhân | Hành động |
| :---- | :---- | :---- |
| 1 | Hệ thống | Hiển thị danh sách chủ đề hội thoại và các tình huống giao tiếp gợi ý. |
| 2 | Người dùng | Chọn một chủ đề và bấm bắt đầu cuộc trò chuyện. |
| 3 | Hệ thống | Khởi tạo phòng hội thoại và gửi câu chào mở đầu bằng chữ Hán kèm phiên âm Pinyin và âm thanh phát âm. |
| 4 | Người dùng | Lựa chọn nhập văn bản hoặc bấm Micro để nói câu tiếng Trung gửi cho AI. |
| 5 | Hệ thống | Phân tích tin nhắn của người dùng, phản hồi câu tiếp theo bằng chữ Hán kèm phiên âm Pinyin và phát âm thanh tương ứng. |
| 6 | Người dùng & Hệ thống | Tiếp tục các lượt đối thoại qua lại theo ngữ cảnh cho đến khi người dùng muốn kết thúc. |
| 7 | Người dùng | Bấm vào nút "Kết thúc trò chuyện". |
| 8 | Hệ thống | Gửi toàn bộ dữ liệu cuộc trò chuyện và chuyển sang xử lý tại Use Case AI-CHAT-RESULT (Đánh giá kết quả hội thoại AI). |
| 9 | \- | Use case kết thúc. |

### **2.3. Luồng rẽ nhánh & Ngoại lệ (Alternative & Exception Flows)**

| Mã luồng | Bước | Tác nhân | Điều kiện & Hành động xử lý |
| :---- | :---- | :---- | :---- |
| **AF-01** | Tại Bước 4 | Người dùng | Thu âm lại tin nhắn giọng nói:• Người dùng hủy bản thu âm chưa ưng ý → Hệ thống xóa bản thu tạm và cho phép bấm Micro để thu âm lại. |
| **AF-02** | Tại Bước 4 | Người dùng | Sử dụng gợi ý phản hồi từ AI (Hint):• Người dùng bấm nút gợi ý → Hệ thống đề xuất 2–3 mẫu câu phản hồi kèm Pinyin phù hợp với ngữ cảnh hiện tại. |
| **AF-03** | Tại Bước 7 | Người dùng | Thoát sớm khi chưa đủ số lượt tối thiểu:• Người dùng bấm nút Thoát khi chưa đủ 3 lượt trò chuyện → Hệ thống cảnh báo chưa đủ dữ liệu để AI chấm điểm và chỉ lưu lịch sử hội thoại mà không chuyển sang AI-CHAT-RESULT. |
| **EF-01** | Tại Bước 5, 8 | Hệ thống | Lỗi kết nối dịch vụ AI:• Không nhận được phản hồi từ AI → Hệ thống hiển thị thông báo: 'Không thể kết nối với dịch vụ AI. Vui lòng thử lại' kèm nút gửi lại tin nhắn. |

### **2.4. Ràng buộc dữ liệu (Validation Rules \- VR)**

| Mã VR | Trường dữ liệu | Ràng buộc kiểm tra | Thông báo lỗi hiển thị |
| :---- | :---- | :---- | :---- |
| **VR01** | Hiển thị chữ Hán và Pinyin | Tất cả các câu hội thoại tiếng Trung của AI và người dùng bắt buộc luôn luôn hiển thị đầy đủ chữ Hán và phiên âm Pinyin | Dữ liệu phiên âm Pinyin không hợp lệ |
| **VR02** | Dữ liệu tin nhắn văn bản | Bắt buộc có nội dung, độ dài từ 1 đến 500 ký tự | Nội dung tin nhắn không được để trống |
| **VR03** | Dữ liệu tin nhắn giọng nói | Thời lượng ghi âm tối thiểu từ 0.5 giây trở lên (không giới hạn thời gian tối đa) | Thời lượng ghi âm phải từ 0.5 giây trở lên |
| **VR04** | Điều kiện chuyển tiếp chấm điểm | Cuộc trò chuyện bắt buộc phải đạt tối thiểu từ 3 lượt đối thoại 2 chiều trở lên mới chuyển sang Use Case AI-CHAT-RESULT | Cần tối thiểu 3 lượt trò chuyện để chuyển sang đánh giá kết quả |
| VR05 | Hạn mức lượt sử dụng AI (Tài khoản Free) | Tài khoản Free được sử dụng tối đa 10 lượt hội thoại AI mỗi ngày (giá trị khởi tạo, có thể cấu hình theo hệ thống; hạn mức tự động làm mới vào 00:00 theo múi giờ địa phương). Tài khoản Premium không giới hạn lượt. Khi hết lượt, hệ thống chặn gửi tin nhắn mới và hiển thị thông báo lỗi. (Tham chiếu PRACTICE-01 BR02) | Bạn đã hết lượt sử dụng AI miễn phí hôm nay. Vui lòng quay lại vào ngày mai hoặc nâng cấp Premium. |

### **2.5. Quy tắc nghiệp vụ (Business Rules \- BR)**

| Mã BR | Tên quy tắc | Nội dung chi tiết |
| :---- | :---- | :---- |
| **BR01** | Môi trường thuần tiếng Trung & Pinyin | Toàn bộ cuộc hội thoại sử dụng 100% chữ Hán và phiên âm Pinyin, không hỗ trợ bản dịch tiếng Việt nhằm tối ưu môi trường phản xạ ngôn ngữ thực tế. |
| **BR02** | Tương tác đa phương thức linh hoạt | Người dùng có thể chuyển đổi tự do giữa gõ văn bản và nói bằng Micro ở bất kỳ lượt đối thoại nào trong cùng một phiên chat. |
| **BR03** | Chuyển tiếp sang AI-CHAT-RESULT | Sau khi người dùng bấm 'Kết thúc trò chuyện' với tối thiểu 3 lượt đối thoại, hệ thống đóng phiên chat và chuyển toàn bộ lịch sử tin nhắn sang Use Case AI-CHAT-RESULT để phân tích đánh giá. |

## **3\. Chức năng Đánh giá kết quả hội thoại AI (AI-CHAT-RESULT: AI Conversation Assessment Report)**

### **3.1. Thông tin chung**

| Mã chức năng | AI-CHAT-RESULT |
| :---- | :---- |
| **Tác nhân (Actor)** | Người học (Learner) |
| **Mục tiêu** | Tiếp nhận dữ liệu hội thoại từ AI-FREE-CHAT, phân tích và hiển thị báo cáo tổng quan năng lực giao tiếp (5 tiêu chí: Từ vựng, Ngữ pháp, Phát âm, Phản xạ, Độ tự nhiên), thống kê phiên trò chuyện và bảng phân tích chi tiết các lỗi sai kèm cách sửa chuẩn từ AI |
| **Tiền điều kiện (Pre-condition)** | Hệ thống đã nhận được dữ liệu phiên hội thoại hoàn chỉnh từ Use Case AI-FREE-CHAT |
| **Hậu điều kiện (Post-condition)** | Ghi nhận điểm số năng lực, cộng điểm kinh nghiệm (XP), lưu các câu lỗi vào Ngân hàng câu cần ôn tập và cập nhật tiến độ học tập |

### **3.2. Luồng chính (Main Flow)**

| Bước | Tác nhân | Hành động |
| :---- | :---- | :---- |
| 1 | Hệ thống | Tiếp nhận toàn bộ dữ liệu phiên hội thoại từ AI-FREE-CHAT và gửi đến mô-đun AI để phân tích. |
| 2 | Hệ thống | Tính toán điểm tổng quan AI, các chỉ số thống kê (Điểm XP nhận được, số lượt nói, số lỗi cần sửa) và điểm 5 tiêu chí năng lực hội thoại (Từ vựng, Ngữ pháp, Phát âm, Phản xạ, Độ tự nhiên). |
| 3 | Hệ thống | Trích xuất danh sách các lỗi sai, phân loại danh mục lỗi (Ngữ pháp, Phát âm, Dùng từ), tạo câu sửa chuẩn kèm giải thích lý do sai và quy tắc ngữ pháp đúng. |
| 4 | Hệ thống | Hiển thị màn hình Báo cáo tổng quan gồm: Điểm tổng quan AI, lời nhận xét từ AI, thống kê phiên chat (XP, Lượt nói, Lỗi cần sửa), bảng điểm 5 tiêu chí năng lực hội thoại, danh sách tóm tắt lỗi cần sửa, nút "Xem chi tiết lỗi" và nút "Tiếp tục học". |
| 5 | Người dùng | Bấm vào nút "Xem chi tiết lỗi". |
| 6 | Hệ thống | Hiển thị màn hình Chi tiết lỗi sai gồm: Bộ lọc danh mục lỗi (Tất cả, Ngữ pháp, Phát âm, Dùng từ), danh sách chi tiết từng lỗi (Đối chiếu câu Bạn nói vs AI sửa kèm Pinyin, giải thích chi tiết, quy tắc đúng) và nút "Luyện lại các lỗi này". |
| 7 | Người dùng | Xem chi tiết các lỗi và bấm nút "Về tổng quan". |
| 8 | Hệ thống | Quay trở lại màn hình Báo cáo tổng quan. |
| 9 | Người dùng | Bấm vào nút "Tiếp tục học". |
| 10 | Hệ thống | Cộng điểm XP, lưu dữ liệu đánh giá vào hồ sơ học tập và quay về trang Luyện tập. |
| 11 | \- | Use case kết thúc. |

### **3.3. Luồng rẽ nhánh & Ngoại lệ (Alternative & Exception Flows)**

| Mã luồng | Bước | Tác nhân | Điều kiện & Hành động xử lý |
| :---- | :---- | :---- | :---- |
| **AF-01** | Tại Bước 4 | Người dùng | Bỏ qua xem chi tiết lỗi:• Người dùng bấm trực tiếp nút 'Tiếp tục học' tại màn hình tổng quan → Hệ thống thực hiện Bước 10 để kết thúc. |
| **AF-02** | Tại Bước 6 | Người dùng | Lọc lỗi theo danh mục:• Người dùng bấm chọn tab danh mục (Ngữ pháp / Phát âm / Dùng từ) → Hệ thống chỉ hiển thị danh sách các lỗi thuộc danh mục được chọn. |
| **AF-03** | Tại Bước 6 | Người dùng | Luyện lại các lỗi sai:• Người dùng bấm nút 'Luyện lại các lỗi này' → Hệ thống khởi tạo phiên luyện tập tương tác tập trung vào các câu/từ vựng bị lỗi. |
| **AF-04** | Tại Bước 4 | Hệ thống | Hội thoại hoàn hảo (0 lỗi cần sửa):• Phiên trò chuyện không phát hiện lỗi sai → Hệ thống ẩn mục lỗi cần sửa và hiển thị thông điệp khen ngợi thành tích xuất sắc. |
| **EF-01** | Tại Bước 1 | Hệ thống | Lỗi phân tích dữ liệu AI (Mất mạng / Lỗi máy chủ AI):• Không phân tích được báo cáo → Hệ thống hiển thị thông báo: 'Không thể tải báo cáo đánh giá lúc này. Vui lòng thử lại' kèm nút Thử lại. |

### **3.4. Ràng buộc dữ liệu (Validation Rules \- VR)**

| Mã VR | Trường dữ liệu | Ràng buộc dữ liệu | Thông báo hiển thị |
| :---- | :---- | :---- | :---- |
| VR01 | Điểm tổng quan AI | • Là số nguyên trong khoảng từ 0 đến 100\.• Tính theo trọng số 5 tiêu chí: Từ vựng (25%), Ngữ pháp (25%), Phát âm (20%), Phản xạ (15%), Độ tự nhiên (15%) (theo BR02). | "Dữ liệu điểm đánh giá không hợp lệ" |
| VR02 | Điểm 5 tiêu chí năng lực hội thoại | • Mỗi tiêu chí là số nguyên trong khoảng từ 0 đến 100\.• Bắt buộc hiển thị đủ 5 tiêu chí; riêng tiêu chí Phát âm được loại trừ nếu phiên hội thoại chỉ dùng gõ văn bản (theo BR03). | "Dữ liệu điểm đánh giá không hợp lệ" |
| VR03 | Dữ liệu phiên hội thoại đầu vào | • Bắt buộc nhận được dữ liệu phiên hội thoại hoàn chỉnh từ AI-FREE-CHAT.• Bắt buộc có kết quả phân tích từ mô-đun AI trước khi hiển thị báo cáo. | "Không thể tải báo cáo đánh giá lúc này. Vui lòng thử lại" |

### **3.5. Quy tắc nghiệp vụ (Business Rules \- BR)**

| Mã BR | Tên quy tắc | Nội dung chi tiết |
| :---- | :---- | :---- |
| **BR01** | Phân loại danh mục lỗi | Mọi lỗi sai trong hội thoại được tự động phân loại vào 3 danh mục chuẩn: (1) Ngữ pháp, (2) Phát âm, (3) Dùng từ. |
| **BR02** | Trọng số tính Điểm tổng quan AI | Điểm tổng quan AI là trung bình có trọng số của 5 tiêu chí: Từ vựng (25%), Ngữ pháp (25%), Phát âm (20%), Phản xạ (15%), Độ tự nhiên (15%). |
| **BR03** | Cơ chế tính điểm khi không dùng Micro | Nếu phiên hội thoại chỉ dùng gõ văn bản (không thu âm giọng nói), tiêu chí Phát âm sẽ được tự động loại trừ và tính điểm tổng quan dựa trên 4 tiêu chí còn lại. |
| **BR04** | Lưu vết vào ngân hàng lỗi cá nhân | Toàn bộ các lỗi sai phát sinh trong buổi hội thoại được tự động đồng bộ vào danh mục 'Sửa lỗi sai cá nhân' trong mục Luyện tập để người học có thể ôn lại bất kỳ lúc nào. |
| BR05 | Quy tắc cộng điểm XP | XP cộng theo quy tắc khối Luyện tập (không theo LRN-02 BR01); cộng tại Bước 10 khi bấm "Tiếp tục học". |

## **4\. Chức năng Xem danh sách & Tiến độ các bộ Flashcard (FLASHCARD-01: View Flashcard Decks & Progress)**

### **4.1. Thông tin chung**

| Mã chức năng | FLASHCARD-01 |
| :---- | :---- |
| **Tác nhân (Actor)** | Người học (Learner) |
| **Mục tiêu** | Hiển thị tổng quan tiến độ làm chủ từ vựng, danh sách toàn bộ các bộ thẻ Flashcard và trạng thái % hoàn thành của từng bộ |
| **Tiền điều kiện (Pre-condition)** | Người dùng đang mở ứng dụng, truy cập vào mục Flashcard từ Trung tâm Luyện tập (PRACTICE-01) |
| **Hậu điều kiện (Post-condition)** | Hiển thị đầy đủ danh sách bộ thẻ và dữ liệu tiến độ mới nhất của người dùng |

### **4.2. Luồng chính (Main Flow)**

| Bước | Tác nhân | Hành động |
| :---- | :---- | :---- |
| **1** | Người dùng | Truy cập vào màn hình Danh mục Flashcard từ Trung tâm Luyện tập (PRACTICE-01). |
| **2** | Hệ thống | Lấy dữ liệu học tập của người dùng và tính toán thống kê tiến độ. |
| **3** | Hệ thống | Hiển thị thông tin tổng quan:• Tổng số từ đã thuộc / Tổng số từ toàn hệ thống (kèm thanh % tổng).• Số lượng bộ thẻ đã đạt 100%, số bộ đang học và số bộ mới. |
| **4** | Hệ thống | Hiển thị danh sách các bộ thẻ Flashcard phân theo danh mục, mỗi bộ gồm:• Tên bộ thẻ, số từ đã thuộc / tổng số từ trong bộ.• Thanh tiến độ % hoàn thành và trạng thái tương ứng (0%, 1-99%, 100% 👑).• Nút hành động tương ứng: \[Bắt đầu học\] / \[Tiếp tục học\] / \[Ôn tập lại\]. |
| **5** | Người dùng | Xem tiến độ hoặc bấm chọn 1 bộ thẻ bất kỳ để bắt đầu học. |
| **6** | Hệ thống | Chuyển hướng sang Use Case Học từ vựng qua Flashcard (FLASHCARD-02) với bộ thẻ đã chọn. |
| **7** | \- | Use case kết thúc. |

### **4.3. Luồng rẽ nhánh & Ngoại lệ (Alternative & Exception Flows)**

| Mã luồng | Bước | Tác nhân | Điều kiện & Hành động xử lý |
| :---- | :---- | :---- | :---- |
| **AF-01** | Tại Bước 4 | Người dùng | Chọn Tab lọc danh mục (HSK, Giao tiếp, Sổ từ đã lưu...): Hệ thống lọc và chỉ hiển thị các bộ thẻ thuộc danh mục được chọn. |
| **AF-02** | Tại Bước 4 | Người dùng | Tìm kiếm bộ thẻ: Nhập từ khóa tìm kiếm \-\> Hệ thống hiển thị các bộ thẻ có tên khớp với từ khóa. |
| **AF-03** | Tại Bước 4 | Người dùng | Bấm nút \[Quay lại\]: Hệ thống quay trở lại màn hình Luyện tập chính (PRACTICE-01). |
| **AF-04** | Tại Bước 2 | Hệ thống | Lỗi tải dữ liệu / Mất mạng: Hiển thị dữ liệu bộ thẻ lưu trữ offline gần nhất trên máy và thông báo cho người dùng. |

### **4.4. Ràng buộc dữ liệu (Validation Rules \- VR)**

| Mã VR | Trường dữ liệu | Ràng buộc dữ liệu | Thông báo hiển thị |
| :---- | :---- | :---- | :---- |
| VR01 | Tiến độ bộ thẻ (% hoàn thành) | • Là số nguyên trong khoảng từ 0 đến 100\.• Xác định trạng thái hiển thị: Mới (0%), Đang học (1% \- 99%), Đã làm chủ (100% 👑). | "Dữ liệu tiến độ bộ thẻ không hợp lệ" |
| VR02 | Số lượng từ trong bộ thẻ | • Tổng số từ của bộ thẻ bắt buộc lớn hơn 0\.• Số từ đã thuộc không được lớn hơn tổng số từ của bộ. | "Dữ liệu bộ thẻ không hợp lệ" |
| VR03 | Trạng thái bộ thẻ | • Chỉ nhận 1 trong 3 giá trị: Mới, Đang học, Đã làm chủ.• Nút hành động hiển thị tương ứng: \[Bắt đầu học\] / \[Tiếp tục học\] / \[Ôn tập lại\]. | "Dữ liệu bộ thẻ không hợp lệ" |

### **4.5. Quy tắc nghiệp vụ (Business Rules \- BR)**

| Mã BR | Tên quy tắc | Nội dung chi tiết |
| :---- | :---- | :---- |
| **BR01** | Chính sách Mở tự do toàn bộ thẻ | Tất cả các bộ thẻ Flashcard đều mở tự do (Open Access). Người dùng có quyền chọn học bất kỳ bộ thẻ nào mà không bị ràng buộc khóa theo cấp độ. |
| **BR02** | 3 Trạng thái Hiển thị Bộ thẻ | • Mới (0%): Chưa học từ nào.• Đang học (1% \- 99%): Hiển thị thanh tiến độ kèm thông báo số từ còn lại để thôi thúc hoàn thành.• Đã làm chủ (100%): Gắn Huy hiệu Vương miện Vàng 👑 vinh danh. |
| **BR03** | Tự động đồng bộ Sổ từ đã lưu | Bộ thẻ 'Sổ từ đã lưu' tự cập nhật số lượng khi người dùng lưu từ mới từ màn hình Tra cứu từ điển (DICT-01). |

## **5\. Chức năng Xem danh sách từ Flashcard đã lưu (FC-LIST: View Saved Flashcards List)**

### **5.1. Thông tin chung**

| Mã chức năng | FC-LIST |
| :---- | :---- |
| **Tên chức năng** | Xem danh sách từ Flashcard đã lưu (View Saved Flashcards List) |
| **Tác nhân (Actor)** | Người học (Learner) |
| **Mục tiêu** | Xem danh sách các từ vựng đã lưu trong sổ từ cá nhân |
| **Tiền điều kiện (Pre-condition)** | Người học bấm chọn vào mục "Sổ từ đã lưu" |
| **Hậu điều kiện (Post-condition)** | Hiển thị danh sách từ vựng và tổng số lượng từ hiện có |

### **5.2. Luồng chính (Main Flow)**

| Bước | Tác nhân | Hành động |
| :---- | :---- | :---- |
| **1** | Người học | Bấm chọn mục "Sổ từ đã lưu". |
| **2** | Hệ thống | Tải và hiển thị: Tổng số lượng từ hiện có trong sổ, Thanh tìm kiếm và danh sách thẻ từ gồm: Chữ Hán, Nghĩa tiếng Việt, Nút phát âm cùng các nút thao tác (Học, Thêm, Sửa, Xóa); trong đó nút Sửa chỉ hiển thị với thẻ do người học tự tạo, nút Xóa hiển thị với mọi thẻ trong sổ từ của người học (theo BR05 và FC-DELETE VR01). |
| **3** | \- | Kết thúc chức năng. |

### **5.3. Luồng rẽ nhánh & Ngoại lệ (Alternative & Exception Flows)**

| Mã luồng | Điều kiện | Hành động xử lý |
| :---- | :---- | :---- |
| **AF-01** | Bấm nút Học | Người học bấm nút "Học ngay / Luyện tập" ➔ Hệ thống chuyển sang Use Case FLASHCARD-02: Học Flashcard (Lật thẻ SRS). |
| **AF-02** | Bấm thêm từ mới | Người học bấm nút "Thêm mới (+)" ➔ Hệ thống chuyển sang Use Case FC-CREATE: Thêm từ Flashcard thủ công. |
| **AF-03** | Bấm sửa từ | Người học bấm vào thẻ từ tự tạo ➔ Hệ thống chuyển sang Use Case FC-UPDATE: Chỉnh sửa từ Flashcard tự tạo. |
| **AF-04** | Bấm xóa từ | Người học bấm nút "Xóa" tại một thẻ từ ➔ Hệ thống chuyển sang Use Case FC-DELETE: Xóa từ Flashcard đã lưu. |
| **AF-05** | Tìm kiếm từ vựng | • Người học nhập từ khóa (Chữ Hán hoặc Nghĩa tiếng Việt) vào ô tìm kiếm ➔ Hệ thống lọc và hiển thị ngay các từ khớp từ khóa.• Nếu bấm nút xóa (X) trên ô tìm kiếm ➔ Hiển thị lại toàn bộ danh sách ban đầu. |
| **AF-06** | Tìm kiếm không có kết quả | Nếu từ khóa nhập vào không khớp với từ nào trong sổ ➔ Hiển thị thông báo: "Không tìm thấy từ vựng phù hợp". |
| **AF-07** | Sổ từ chưa có từ nào | Hiển thị thông báo: "Chưa có từ vựng nào trong sổ từ" kèm nút "Khám phá từ vựng". |
| **AF-08** | Bấm nút Quay lại | Đưa người học quay về màn hình trước đó. |
| **EF-01** | Lỗi tải dữ liệu | Hiển thị thông báo: "Không thể tải danh sách từ. Vui lòng thử lại" kèm nút "Thử lại". |

### **5.4. Ràng buộc dữ liệu (Validation Rules \- VR)**

| Mã VR | Trường dữ liệu | Ràng buộc | Thông báo lỗi |
| :---- | :---- | :---- | :---- |
| **VR01** | Phiên đăng nhập | Tài khoản còn hạn đăng nhập. | "Phiên đăng nhập hết hạn" |
| **VR02** | Từ khóa tìm kiếm | Tối đa 50 ký tự. | "Từ khóa tìm kiếm quá dài" |

### **5.5. Quy tắc nghiệp vụ (Business Rules \- BR)**

| Mã BR | Tên quy tắc | Nội dung |
| :---- | :---- | :---- |
| **BR01** | Sắp xếp | Mặc định xếp từ mới lưu nhất lên đầu danh sách. |
| **BR02** | Bộ đếm từ | Luôn cập nhật và hiển thị chính xác tổng số từ hiện có trên thanh tiêu đề. |
| **BR03** | Phát âm | Cho phép bấm nghe phát âm từng từ không giới hạn số lần. |
| **BR04** | Tìm kiếm | Cho phép tìm kiếm nhanh theo Chữ Hán hoặc Nghĩa tiếng Việt. |
| BR05 | Hiển thị nút Sửa và nút Xóa theo nguồn thẻ | • Nút "Sửa": chỉ với thẻ do người học tự tạo; thẻ lưu từ hệ thống/bài học/từ điển chỉ xem và phát âm (theo FC-UPDATE BR01).• Nút "Xóa": mọi thẻ trong sổ từ của người học (theo FC-DELETE VR01). |

## **6\. Chức năng Thêm từ Flashcard thủ công (FC-CREATE: Add Custom Flashcard)**

### **6.1. Thông tin chung**

| Mã chức năng | FC-CREATE |
| :---- | :---- |
| **Tên chức năng** | Thêm từ Flashcard thủ công (Add Custom Flashcard) |
| **Tác nhân (Actor)** | Người học (Learner) |
| **Mục tiêu** | Tự tạo thẻ Flashcard mới với nội dung 2 mặt tùy chỉnh |
| **Tiền điều kiện (Pre-condition)** | Người học đang ở màn hình Danh sách từ đã lưu (FC-LIST) |
| **Hậu điều kiện (Post-condition)** | Thẻ từ mới được tạo và xuất hiện ở đầu danh sách FC-LIST |

### **6.2. Luồng chính (Main Flow)**

| Bước | Tác nhân | Hành động |
| :---- | :---- | :---- |
| **1** | Người học | Bấm nút "Thêm mới (+)" trên màn hình FC-LIST. |
| **2** | Hệ thống | Hiển thị khung nhập gồm: Mặt trước (Chữ Hán, pinyin) và Mặt sau (Nghĩa tiếng Việt). |
| **3** | Người học | Nhập nội dung và bấm "Lưu thẻ". |
| **4** | Hệ thống | Kiểm tra dữ liệu, lưu thẻ mới, tăng bộ đếm từ thêm 1 và thông báo: "Thêm từ mới thành công". |
| **5** | \- | Kết thúc chức năng. |

### **6.3. Luồng rẽ nhánh & Ngoại lệ (Alternative & Exception Flows)**

| Mã luồng | Điều kiện | Hành động xử lý |
| :---- | :---- | :---- |
| **AF-01** | Hủy thêm từ | Bấm quay lại ➔ Quay lại màn hình FC-LIST, không lưu từ đó. |
| **AF-02** | Điền pinyin sẵn | Bấm nút AI điền ở ô pinyin ➔AI sẽ dịch từ tiếng hán vừa điền ở trên đúng pinyin và tự điền. |
| **EF-01** | Thiếu nội dung bắt buộc | Báo lỗi viền đỏ: "Vui lòng nhập Mặt trước và Nghĩa tiếng Việt". |
| **EF-02** | Trùng từ đã có | Cảnh báo: "Từ này đã có trong sổ từ" kèm chọn "Xem từ cũ" hoặc "Vẫn tạo". |
| **EF-03** | Lỗi lưu thẻ | Giữ nguyên dữ liệu vừa nhập trên khung và thông báo lỗi. |

### **6.4. Ràng buộc dữ liệu (Validation Rules \- VR)**

| Mã VR | Trường dữ liệu | Ràng buộc | Thông báo lỗi |
| :---- | :---- | :---- | :---- |
| **VR01** | Mặt trước (Chữ Hán) | Bắt buộc, tối đa 50 ký tự. | "Mặt trước không được để trống (tối đa 50 ký tự)" |
| **VR02** | Mặt sau (Nghĩa tiếng Việt) | Bắt buộc, tối đa 200 ký tự. | "Mặt sau không được để trống (tối đa 200 ký tự)" |

### **6.5. Quy tắc nghiệp vụ (Business Rules \- BR)**

| Mã BR | Tên quy tắc | Nội dung |
| :---- | :---- | :---- |
| **BR01** | Trạng thái ban đầu | Từ mới tạo được xếp vào nhóm "Từ mới" và nhắc ôn vào ngày hôm sau. |
| **BR02** | Vị trí hiển thị | Thẻ mới tạo luôn được chèn ở vị trí đầu tiên của danh sách. |
| **BR03** | Phân loại từ | Tự động gắn nhãn "Tự tạo" để phân biệt với từ lưu từ bài học. |
| **BR04** | Giới hạn số lượng | Số lượng từ được tạo tuân thủ theo hạn mức tối đa do hệ thống quy định cho mỗi sổ từ. |

## **7\. Chức năng Chỉnh sửa từ Flashcard tự tạo (FC-UPDATE: Edit Custom Flashcard)**

### **7.1. Thông tin chung**

| Mã chức năng | FC-UPDATE |
| :---- | :---- |
| **Tên chức năng** | Chỉnh sửa từ Flashcard tự tạo (Edit Custom Flashcard) |
| **Tác nhân (Actor)** | Người học (Learner) |
| **Mục tiêu** | Chỉnh sửa lại nội dung Chữ Hán hoặc Nghĩa tiếng Việt của thẻ từ do mình tự tạo |
| **Tiền điều kiện (Pre-condition)** | Người học đang ở màn hình FC-LIST và chọn một thẻ từ thuộc loại "Từ tự tạo" |
| **Hậu điều kiện (Post-condition)** | Thẻ từ được cập nhật nội dung mới trên danh sách |

### **7.2. Luồng chính (Main Flow)**

| Bước | Tác nhân | Hành động |
| :---- | :---- | :---- |
| **1** | Người học | Bấm vào thẻ từ (hoặc icon Chỉnh sửa) của một từ tự tạo trên màn hình FC-LIST. |
| **2** | Hệ thống | Hiển thị màn hình chỉnh sửa với thông tin cũ đã được điền sẵn ở 2 mặt (Mặt trước: Chữ Hán và pinyin, Mặt sau: Nghĩa tiếng Việt). |
| **3** | Người học | Thay đổi thông tin theo nhu cầu và bấm nút "Lưu thay đổi". |
| **4** | Hệ thống | Kiểm tra tính hợp lệ của dữ liệu mới, cập nhật lại thẻ từ và thông báo: "Cập nhật từ vựng thành công". |
| **5** | \- | Kết thúc chức năng. |

### **7.3. Luồng rẽ nhánh & Ngoại lệ (Alternative & Exception Flows)**

| Mã luồng | Điều kiện | Hành động xử lý |
| :---- | :---- | :---- |
| **AF-01** | Bấm vào từ được lưu từ bài học/hệ thống | Hệ thống không mở màn sửa, chỉ cho phép xem chi tiết và phát âm. |
| **AF-02** | Hủy chỉnh sửa | Bấm nút quay lại ➔ Đóng màn sửa, giữ nguyên nội dung cũ của thẻ. |
| **AF-03** | Xóa từ | Bấm “Xóa từ này” ➔ chuyển UC **FC-DELETE: Delete Saved Flashcard** |
| **EF-01** | Xóa hết nội dung bắt buộc | Báo lỗi viền đỏ: "Mặt trước và Mặt sau không được để trống". |
| **EF-02** | Lỗi cập nhật | Giữ nguyên nội dung vừa sửa trên khung và thông báo lỗi. |

### **7.4. Ràng buộc dữ liệu (Validation Rules \- VR)**

| Mã VR | Trường dữ liệu | Ràng buộc | Thông báo lỗi |
| :---- | :---- | :---- | :---- |
| **VR01** | Mặt trước (Chữ Hán) | Bắt buộc, tối đa 50 ký tự. | "Mặt trước không được để trống (tối đa 50 ký tự)" |
| **VR02** | Mặt sau (Nghĩa tiếng Việt) | Bắt buộc, tối đa 200 ký tự. | "Mặt sau không được để trống (tối đa 200 ký tự)" |

### **7.5. Quy tắc nghiệp vụ (Business Rules \- BR)**

| Mã BR | Tên quy tắc | Nội dung |
| :---- | :---- | :---- |
| **BR01** | Phạm vi cho phép chỉnh sửa | Chỉ áp dụng cho từ do chính người học tự tạo. Các từ được lưu từ hệ thống/bài học/từ điển thì không được phép chỉnh sửa nội dung gốc. |
| **BR02** | Giữ nguyên tiến trình học | Việc sửa lại chữ hoặc nghĩa không làm mất đi lịch sử và cấp độ ghi nhớ đã tích lũy của thẻ từ đó. |
| **BR03** | Cập nhật hiển thị tức thời | Nội dung mới sau khi bấm lưu sẽ được cập nhật ngay lập tức trên thẻ từ ngoài danh sách FC-LIST. |

## **8\. Chức năng Xóa từ Flashcard đã lưu (FC-DELETE: Delete Saved Flashcard)**

### **8.1. Thông tin chung**

| Mã chức năng | FC-DELETE |
| :---- | :---- |
| **Tên chức năng** | Xóa từ Flashcard đã lưu (Delete Saved Flashcard) |
| **Tác nhân (Actor)** | Người học (Learner) |
| **Mục tiêu** | Xóa từ vựng khỏi sổ từ cá nhân |
| **Tiền điều kiện (Pre-condition)** | Người học đang ở màn hình Danh sách từ đã lưu (FC-LIST) |
| **Hậu điều kiện (Post-condition)** | Từ vựng bị xóa, tổng số từ giảm đi 1 |

### **8.2. Luồng chính (Main Flow)**

| Bước | Tác nhân | Hành động |
| :---- | :---- | :---- |
| **1** | Người học | Bấm nút "Xóa" tại từ muốn bỏ trên màn hình FC-LIST. |
| **2** | Hệ thống | Hiển thị hộp thoại xác nhận: "Xóa từ này khỏi sổ từ?". |
| **3** | Người học | Bấm "Xác nhận xóa". |
| **4** | Hệ thống | Xóa từ, giảm bộ đếm từ đi 1 và thông báo: "Đã xóa từ thành công". |
| **5** | \- | Kết thúc chức năng. |

### **8.3. Luồng rẽ nhánh & Ngoại lệ (Alternative & Exception Flows)**

| Mã luồng | Điều kiện | Hành động xử lý |
| :---- | :---- | :---- |
| **AF-01** | Hủy xóa | Bấm "Hủy" hoặc bấm ngoài hộp thoại ➔ Đóng hộp thoại, giữ nguyên từ trong FC-LIST. |
| **AF-02** | Xóa hết từ trong sổ | Danh sách về 0 ➔ Chuyển sang màn hình thông báo sổ từ trống. |
| **EF-01** | Lỗi xóa | Thông báo: "Không thể xóa từ lúc này. Vui lòng thử lại". |

### **8.4. Ràng buộc dữ liệu (Validation Rules \- VR)**

| Mã VR | Trường dữ liệu | Ràng buộc | Thông báo lỗi |
| :---- | :---- | :---- | :---- |
| **VR01** | Quyền xóa | Chỉ xóa được từ trong sổ của chính mình. | "Không có quyền thực hiện" |

### **8.5. Quy tắc nghiệp vụ (Business Rules \- BR)**

| Mã BR | Tên quy tắc | Nội dung |
| :---- | :---- | :---- |
| **BR01** | Bắt buộc xác nhận | Luôn hiển thị hộp thoại xác nhận trước khi xóa để tránh bấm nhầm. |
| **BR02** | Hủy lịch ôn tập | Xóa từ đồng nghĩa hủy bỏ toàn bộ lịch nhắc ôn tập của từ đó. |
| **BR03** | Không ảnh hưởng từ điển | Xóa từ chỉ áp dụng trong sổ cá nhân, không ảnh hưởng từ điển chung. |
| **BR04** | Cập nhật tức thì | Bộ đếm từ và tỷ lệ hoàn thành được trừ đi ngay sau khi xóa. |

## **9\. Chức năng Học từ vựng qua Flashcard (FLASHCARD-02: Spaced Repetition Flashcard Learning)**

### **9.1. Thông tin chung**

| Mã chức năng | FLASHCARD-02 |
| :---- | :---- |
| **Tác nhân (Actor)** | Người học (Learner) |
| **Mục tiêu** | Thực hiện phiên học lật thẻ từ vựng và tự đánh giá khả năng ghi nhớ theo thuật toán lặp lại ngắt quãng (SRS) |
| **Tiền điều kiện (Pre-condition)** | Người dùng đã chọn 1 bộ thẻ từ màn hình danh mục Flashcard (FLASHCARD-01) |
| **Hậu điều kiện (Post-condition)** | Cập nhật ngày ôn tập tiếp theo của từng từ; cập nhật lại % tiến độ của bộ thẻ sau phiên học |

### **9.2. Luồng chính (Main Flow)**

| Bước | Tác nhân | Hành động |
| :---- | :---- | :---- |
| **1** | Hệ thống | Tải danh sách các thẻ từ vựng cần học/ôn tập trong bộ thẻ đã chọn. |
| **2** | Hệ thống | Hiển thị mặt trước của thẻ gồm: Mặt chữ Hán, nút phát âm Audio. |
| **3** | Người dùng | Xem mặt trước và bấm nút 'Lật thẻ' (hoặc chạm vào thẻ). |
| **4** | Hệ thống | Hiển thị mặt sau của thẻ gồm:• Mặt chữ Hán, Pinyin, Loại từ, Nghĩa tiếng Việt.• 4 nút đánh giá ghi nhớ: Không nhớ / Khó / Nhớ / Rất dễ. |
| **5** | Người dùng | Chọn 1 mức đánh giá phù hợp với khả năng ghi nhớ của mình. |
| **6** | Hệ thống | Ghi nhận kết quả, tính toán ngày ôn tiếp theo cho từ đó và chuyển sang thẻ tiếp theo. |
| **7** | \- | Lặp lại từ Bước 2 đến Bước 6 cho đến khi hoàn thành toàn bộ số thẻ trong phiên học. |
| **8** | Hệ thống | Cập nhật lại % tiến độ hoàn thành của bộ thẻ và hiển thị màn hình tổng kết (Số thẻ đã học, số từ đã thuộc, trạng thái bộ thẻ). |
| **9** | \- | Use case kết thúc. |

### **9.3. Luồng rẽ nhánh & Ngoại lệ (Alternative & Exception Flows)**

| Mã luồng | Bước | Tác nhân | Điều kiện & Hành động xử lý |
| :---- | :---- | :---- | :---- |
| **AF-01** | Tại Bước 5 | Người dùng | Chọn mức 'Không nhớ': Thẻ này được đưa về cuối phiên học để người dùng ôn lại ngay trong buổi. |
| **AF-02** | Tại Bước 2 / 4 | Người dùng | Bấm nút 'Phát âm': Hệ thống phát lại audio giọng đọc của từ vựng (tùy chọn tốc độ 1.0x hoặc 0.75x). |
| **AF-03** | Tại Bước 1 | Người dùng | Học lại bộ thẻ đã đạt 100%: Người dùng vẫn học bình thường để củng cố trí nhớ. |
| **AF-04** | Bất kỳ | Người dùng | Bấm nút 'Tạm dừng / Thoát': Hệ thống lưu lại tiến độ phiên học và quay về màn hình Danh mục Flashcard (FLASHCARD-01). |
| **AF-05** | Bất kỳ | Hệ thống | Mất kết nối Internet: Hệ thống lưu tiến độ và kết quả đánh giá cục bộ trên thiết bị (Offline Cache), tự động đồng bộ khi có mạng trở lại. |

### **9.4. Ràng buộc dữ liệu (Validation Rules \- VR)**

| Mã VR | Trường dữ liệu | Ràng buộc kiểm tra | Thông báo lỗi hiển thị |
| :---- | :---- | :---- | :---- |
| **VR01** | Số lượng thẻ/phiên | Mỗi phiên học giới hạn từ 5 đến tối đa 30 thẻ | Không có thẻ từ vựng nào để học |
| **VR02** | Mức đánh giá ghi nhớ | Bắt buộc chọn đúng 1 trong 4 giá trị: AGAIN (Không nhớ), HARD (Khó), GOOD (Nhớ), EASY (Rất dễ) | Vui lòng chọn mức độ ghi nhớ |

### **9.5. Quy tắc nghiệp vụ (Business Rules \- BR)**

| Mã BR | Tên quy tắc | Nội dung chi tiết |
| :---- | :---- | :---- |
| **BR01** | Tiêu chuẩn tính từ 'Đã thuộc' | Một thẻ từ vựng được tính là 'Đã thuộc' đóng góp vào tiến độ % của bộ thẻ khi người dùng đánh giá mức 'Rất dễ' (hoặc đạt mức 'Nhớ' liên tiếp trong 3 chu kỳ ôn tập). |
| **BR02** | Khoảng cách chu kỳ lặp lại (SRS Intervals) | • Không nhớ (Again): Lặp lại sau 1 ngày (và học lại ngay trong phiên hiện tại).• Khó (Hard): Lặp lại sau 2 \- 3 ngày.• Nhớ (Good): Lặp lại sau 5 \- 7 ngày.• Rất dễ (Easy): Lặp lại sau 14 \- 30 ngày. |
| **BR03** | Cơ chế cập nhật % Tiến độ bộ thẻ | Sau khi kết thúc phiên học, hệ thống tự động tính toán lại tỷ lệ % của bộ thẻ. Nếu đạt 100%, bộ thẻ được chuyển sang trạng thái 'Đã làm chủ' 👑 trên màn hình danh mục (FLASHCARD-01). |
| **BR04** | Bảo lưu dữ liệu cá nhân | Tiến độ học tập và mức độ ghi nhớ của từng từ được lưu trữ độc lập theo (UserID, WordID), đảm bảo không bị mất dữ liệu khi chuyển qua lại giữa các bộ thẻ. |

## **10\. Chức năng Xem danh sách chủ đề luyện nghe (LISTEN-01: View Listening Topics)**

### **10.1. Thông tin chung**

| Mã chức năng | LISTEN-01 |
| :---- | :---- |
| **Tác nhân (Actor)** | Người học (Learner) |
| **Mục tiêu** | Hiển thị danh sách các chủ đề luyện nghe kèm số lượng bài có trong mỗi chủ đề |
| **Tiền điều kiện (Pre-condition)** | Đang ở màn hình Luyện tập, thiết bị có kết nối mạng |
| **Hậu điều kiện (Post-condition)** | Hiển thị danh sách các chủ đề luyện nghe |

### **10.2. Luồng chính (Main Flow)**

| Bước | Tác nhân | Hành động |
| :---- | :---- | :---- |
| **1** | Người dùng | Bấm chọn mục "Luyện nghe" từ màn hình Luyện tập. |
| **2** | Hệ thống | Tải và hiển thị danh sách các Chủ đề luyện nghe (Icon minh họa, Tên chủ đề tiếng Việt, Số lượng bài nghe trong chủ đề). |
| **3** | Người dùng | Xem danh sách chủ đề. Use case kết thúc. (Khi người dùng bấm chọn 1 chủ đề, hệ thống chuyển sang UC LISTEN-02). |

### **10.3. Luồng rẽ nhánh & Ngoại lệ (Alternative & Exception Flows)**

| Mã luồng | Bước | Tác nhân | Điều kiện & Hành động xử lý |
| :---- | :---- | :---- | :---- |
| **AF-01** | Bước 2 | Hệ thống | Lỗi kết nối / Mất mạng: Hiển thị thông báo "Không thể tải danh sách chủ đề. Vui lòng thử lại". |
| **AF-02** | Bất kỳ | Người dùng | Bấm nút Quay lại (←): Đóng danh sách và quay về màn hình trước. Use case kết thúc. |

### **10.4. Ràng buộc dữ liệu (Validation Rules \- VR)**

| Mã VR | Trường dữ liệu | Ràng buộc kiểm tra | Thông báo lỗi |
| :---- | :---- | :---- | :---- |
| **VR01** | Số lượng bài | Số lượng bài hiển thị là số nguyên dương ≥ 0\. | \- |

### **10.5. Quy tắc nghiệp vụ (Business Rules \- BR)**

| Mã BR | Tên quy tắc | Nội dung chi tiết |
| :---- | :---- | :---- |
| **BR01** | Hiển thị số lượng bài | Hệ thống tự động đếm và hiển thị đúng tổng số lượng bài nghe thuộc về từng chủ đề. |

## **11\. Chức năng Xem danh sách bài nghe theo chủ đề (LISTEN-02: View Lessons by Topic)**

### **11.1. Thông tin chung**

| Mã chức năng | LISTEN-02 |
| :---- | :---- |
| **Tác nhân (Actor)** | Người học (Learner) |
| **Mục tiêu** | Hiển thị danh sách các bài nghe thuộc chủ đề đã chọn gồm tên bài, thời lượng và phân loại HSK |
| **Tiền điều kiện (Pre-condition)** | Đã chọn 1 chủ đề từ LISTEN-01 |
| **Hậu điều kiện (Post-condition)** | Hiển thị danh sách bài nghe chi tiết kèm trạng thái mở khóa theo gói tài khoản |

### **11.2. Luồng chính (Main Flow)**

| Bước | Tác nhân | Hành động |
| :---- | :---- | :---- |
| **1** | Hệ thống | Tải và hiển thị danh sách các bài nghe theo chủ đề: Tên bài nghe (Chữ Hán \+ Tiếng Việt), Thời lượng audio, Phân loại HSK (HSK 1 \- 6), Trạng thái mở khóa/khóa theo quyền tài khoản. |
| **2** | Người dùng | Lướt xem danh sách các bài nghe. |
| **3** | Người dùng | Bấm chọn 1 bài nghe hợp lệ đã mở khóa. |
| **4** | Hệ thống | Chuyển sang UC LISTEN-03: Nghe cuộc hội thoại. |

### **11.3. Luồng rẽ nhánh & Ngoại lệ (Alternative & Exception Flows)**

| Mã luồng | Bước | Tác nhân | Điều kiện & Hành động xử lý |
| :---- | :---- | :---- | :---- |
| **AF-01** | Bước 1 | Hệ thống | Lỗi tải dữ liệu: Báo lỗi "Không thể tải danh sách bài nghe". |
| **AF-02** | Bất kỳ | Người dùng | Bấm nút Quay lại (←): Quay về màn hình danh sách chủ đề (LISTEN-01). |
| **AF-03** | Bước 2 | Người dùng | Chọn tab lọc Cấp độ (HSK 1 \-\> HSK 6): Hệ thống lọc và chỉ hiển thị danh sách các bài nghe thuộc cấp độ HSK tương ứng (Chọn HSK 1 hiện bài HSK 1, chọn HSK 2 hiện bài HSK 2... chọn Tất cả để hiện toàn bộ). |
| **AF-04** | Bước 3 | Người dùng | Tài khoản Free bấm nút "Nâng cấp Premium" tại bài bị khóa (HSK 4 \- 6): Hệ thống chuyển hướng trực tiếp sang màn hình Nâng cấp tài khoản Premium. |

### **11.4. Ràng buộc dữ liệu (Validation Rules \- VR)**

| Mã VR | Trường dữ liệu | Ràng buộc kiểm tra | Thông báo lỗi |
| :---- | :---- | :---- | :---- |
| **VR01** | Phân loại HSK | Phân loại từ HSK 1 đến HSK 6\. | \- |
| **VR02** | Thời lượng bài nghe | Định dạng thời gian chuẩn phút:giây (mm:ss). | \- |

### **11.5. Quy tắc nghiệp vụ (Business Rules \- BR)**

| Mã BR | Tên quy tắc | Nội dung chi tiết |
| :---- | :---- | :---- |
| **BR01** | Phân loại bài nghe | Mỗi bài nghe được gán nhãn cấp độ HSK tương ứng với độ khó của từ vựng và ngữ pháp trong bài. |
| **BR02** | Phân quyền tài khoản (Free vs Premium) | • Tài khoản Free: Mở khóa các bài nghe thuộc HSK 1, HSK 2, HSK 3 (hiển thị nút "Luyện nghe ▶"). Các bài thuộc HSK 4, HSK 5, HSK 6 ở trạng thái khóa và hiển thị nút "Nâng cấp Premium".• Tài khoản Premium: Mở khóa toàn bộ 100% các bài nghe từ HSK 1 đến HSK 6\. |

## **12\. Chức năng Nghe cuộc hội thoại (LISTEN-03: Listen to Conversation)**

### **12.1. Thông tin chung**

| Mã chức năng | LISTEN-03 |
| :---- | :---- |
| **Tác nhân (Actor)** | Người học (Learner) |
| **Mục tiêu** | Nghe phát âm thanh bài hội thoại, có thể xem transcript đồng bộ và chuyển sang luyện tập |
| **Tiền điều kiện (Pre-condition)** | Đã chọn 1 bài nghe từ LISTEN-02 |
| **Hậu điều kiện (Post-condition)** | Người học hoàn thành phiên nghe bài hội thoại |

### **12.2. Luồng chính (Main Flow)**

| Bước | Tác nhân | Hành động |
| :---- | :---- | :---- |
| **1** | Hệ thống | Mở giao diện bài nghe: Khung phát Audio (Nút Play/Pause, thanh thời gian), nút "Xem Transcript", nút "Luyện tập". |
| **2** | Người dùng | Bấm nút Play (Nghe). |
| **3** | Hệ thống | Tải và phát âm thanh bài hội thoại, thanh thời gian chạy đồng bộ. |
| **4** | Người dùng | Nghe nội dung bài hội thoại, có thể tạm dừng (Pause) hoặc tua đoạn. |

### **12.3. Luồng rẽ nhánh & Ngoại lệ (Alternative & Exception Flows)**

| Mã luồng | Bước | Tác nhân | Điều kiện & Hành động xử lý |
| :---- | :---- | :---- | :---- |
| **AF-01** | Bất kỳ | Người dùng | Bấm nút "Xem Transcript": Hệ thống hiển thị danh sách các câu lời thoại (Chữ Hán \+ Pinyin \+ Dịch nghĩa). Khi âm thanh phát đến câu nào, hệ thống tự động Highlight (làm sáng nổi bật) câu đó theo thời gian thực. |
| **AF-02** | Bất kỳ | Người dùng | Bấm nút "Luyện tập": Hệ thống dừng phát âm thanh và chuyển sang UC LISTEN-04: Luyện tập trả lời câu hỏi. |
| **AF-03** | Bước 3 | Hệ thống | Lỗi phát âm thanh / Mất mạng: Báo lỗi "Không thể phát âm thanh. Vui lòng thử lại". |
| **AF-04** | Bất kỳ | Người dùng | Bấm nút Quay lại (←): Dừng audio và quay về danh sách bài nghe (LISTEN-02). |

### **12.4. Ràng buộc dữ liệu (Validation Rules \- VR)**

| Mã VR | Trường dữ liệu | Ràng buộc kiểm tra | Thông báo lỗi |
| :---- | :---- | :---- | :---- |
| **VR01** | Tốc độ phát | Các mốc tốc độ: 0.75x, 1.0x (mặc định), 1.25x. | \- |

### **12.5. Quy tắc nghiệp vụ (Business Rules \- BR)**

| Mã BR | Tên quy tắc | Nội dung chi tiết |
| :---- | :---- | :---- |
| **BR01** | Đồng bộ Transcript | Khi bật xem transcript, câu thoại đang phát trong audio sẽ tự động được cuộn đến và Highlight nổi bật theo âm thanh. |
| **BR02** | Tự động tạm dừng | Audio tự động Pause khi người dùng chuyển sang làm bài tập hoặc thoát màn hình nghe. |

## **13\. Chức năng Luyện tập bài nghe (LISTEN-04: Practice Conversation Quiz)**

### **13.1. Thông tin chung**

| Mã chức năng | LISTEN-04 |
| :---- | :---- |
| **Tác nhân (Actor)** | Người học (Learner) |
| **Mục tiêu** | Trả lời bộ câu hỏi trắc nghiệm kiểm tra và củng cố mức độ hiểu nội dung bài nghe |
| **Tiền điều kiện (Pre-condition)** | Người dùng bấm nút "Luyện tập" từ LISTEN-03 |
| **Hậu điều kiện (Post-condition)** | Chấm điểm, hiển thị kết quả Đúng/Sai kèm giải thích chi tiết và tổng điểm |

### **13.2. Luồng chính (Main Flow)**

| Bước | Tác nhân | Hành động |
| :---- | :---- | :---- |
| **1** | Hệ thống | Lấy ngẫu nhiên bộ câu hỏi từ ngân hàng đề của bài nghe và hiển thị toàn bộ câu hỏi 1 lần trên màn hình. |
| **2** | Người dùng | Đọc và chọn đáp án cho các câu hỏi. |
| **3** | Người dùng | Bấm nút "Nộp bài". |
| **4** | Hệ thống | Chấm điểm và hiển thị kết quả ngay trên màn hình: Báo Đúng/Sai cho từng câu, hiển thị đáp án đúng, nội dung giải thích chi tiết và tổng điểm đạt được. |
| **5** | Người dùng | Xem giải thích và bấm nút "Hoàn thành". |
| **6** | Hệ thống | Lưu kết quả, cộng điểm thưởng (XP/Gems) và chuyển hướng quay về danh sách bài nghe (LISTEN-02). |

### **13.3. Luồng rẽ nhánh & Ngoại lệ (Alternative & Exception Flows)**

| Mã luồng | Bước | Tác nhân | Điều kiện & Hành động xử lý |
| :---- | :---- | :---- | :---- |
| **AF-01** | Bước 3 | Người dùng | Chưa chọn đủ đáp án mà bấm 'Nộp bài': Hiển thị cảnh báo: "Bạn chưa hoàn thành hết các câu hỏi. Bạn có chắc chắn muốn nộp bài?". |
| **AF-02** | Bất kỳ | Người dùng | Bấm Thoát giữa chừng (X): Hiện popup xác nhận. Nếu đồng ý thì hủy kết quả bài làm và quay về LISTEN-03. |

### **13.4. Ràng buộc dữ liệu (Validation Rules \- VR)**

| Mã VR | Trường dữ liệu | Ràng buộc dữ liệu | Thông báo hiển thị |
| :---- | :---- | :---- | :---- |
| VR01 | Lựa chọn đáp án | Bắt buộc chọn 1 trong các đáp án A, B, C, D. | "Vui lòng chọn một đáp án" |
| VR02 | Tệp âm thanh bài nghe | • File .mp3, .m4a, .aac, .wav (dung lượng ≤ 5MB).• Người học được nghe lại không giới hạn số lần trong bài học. | "Không thể phát âm thanh" |
| VR03 | Bộ câu hỏi & đáp án | • Bắt buộc có đáp án đúng cho từng câu hỏi.• Thứ tự đáp án (A, B, C, D) được hoán vị ngẫu nhiên mỗi lần luyện tập (theo BR01). | "Dữ liệu câu hỏi không hợp lệ" |

### **13.5. Quy tắc nghiệp vụ (Business Rules \- BR)**

| Mã BR | Tên quy tắc | Nội dung chi tiết |
| :---- | :---- | :---- |
| **BR01** | Ngẫu nhiên hóa đề (Random Quiz) | Mỗi lần vào luyện tập, hệ thống tự động bốc ngẫu nhiên câu hỏi và hoán vị thứ tự các đáp án (A, B, C, D) từ ngân hàng câu hỏi để tránh học vẹt. |
| **BR02** | Đánh giá & Giải thích | Sau khi nộp bài, hệ thống hiển thị rõ ràng trạng thái Đúng/Sai kèm giải thích chi tiết cho từng câu hỏi. |
| BR03 | Quy tắc cộng thưởng XP/Gems | Bấm "Hoàn thành" (Bước 6): cộng XP theo quy tắc khối Luyện tập và cộng Gems theo HOME-02 BR02. |

## **14\. Chức năng Xem danh sách chủ đề câu chuyện (STORY-01: View Story Topics)**

### **14.1. Thông tin chung**

| Mã chức năng | STORY-01 |
| :---- | :---- |
| **Tên chức năng** | Xem danh sách chủ đề câu chuyện (View Story Topics) |
| **Tác nhân (Actor)** | Người học (Learner) |
| **Mục tiêu** | Hiển thị danh sách các chủ đề bài đọc câu chuyện tiếng Trung |
| **Tiền điều kiện (Pre-condition)** | Người học đã đăng nhập thành công vào hệ thống |
| **Hậu điều kiện (Post-condition)** | Hiển thị đầy đủ danh sách chủ đề bài đọc để người dùng lựa chọn |

### **14.2. Luồng chính (Main Flow)**

| Bước | Tác nhân | Hành động |
| :---- | :---- | :---- |
| **1** | Người dùng | Bấm chọn chức năng "Luyện đọc" từ màn hình Luyện tập. |
| **2** | Hệ thống | Truy xuất danh sách các chủ đề bài đọc từ cơ sở dữ liệu. |
| **3** | Hệ thống | Hiển thị danh sách các chủ đề bài đọc (Tên chủ đề, hình ảnh đại diện, tổng số câu chuyện, và đoạn mô tả tóm tắt các câu chuyện bên trong). |
| **4** | Người dùng | Xem danh sách và bấm chọn 1 chủ đề. |
| **5** | Hệ thống | Điều hướng sang màn hình Xem danh sách câu chuyện theo chủ đề (STORY-02). |
| **6** | \- | Use case kết thúc. |

### **14.3. Luồng rẽ nhánh & Ngoại lệ (Alternative & Exception Flows)**

| Mã luồng | Tại bước | Tác nhân | Điều kiện & Hành động xử lý |
| :---- | :---- | :---- | :---- |
| **AF-01** | Bước 2 | Hệ thống | Lỗi tải dữ liệu: Thông báo "Không thể tải danh sách chủ đề. Vui lòng thử lại" kèm nút Thử lại. |
| **AF-02** | Bước 3 | Hệ thống | Danh sách trống: Thông báo "Chưa có chủ đề bài đọc nào khả dụng". |
| **AF-03** | Bước 3 | Người dùng | Quay lại màn hình Luyện tập: Người dùng bấm nút Quay lại (Back ←) ➔ Hệ thống điều hướng quay về màn hình Luyện tập. |

### **14.4. Ràng buộc dữ liệu (Validation Rules \- VR)**

| Mã VR | Trường dữ liệu | Ràng buộc dữ liệu | Thông báo hiển thị |
| :---- | :---- | :---- | :---- |
| VR01 | Số lượng câu chuyện của chủ đề | • Tổng số câu chuyện của chủ đề bắt buộc lớn hơn 0 để chủ đề được hiển thị.• Nếu danh sách trống, hệ thống hiển thị thông báo 'Chưa có chủ đề bài đọc nào khả dụng'. | "Dữ liệu chủ đề không hợp lệ" |
| VR02 | Thông tin hiển thị của chủ đề | • Bắt buộc có Tên chủ đề và hình ảnh đại diện.• Đoạn mô tả tóm tắt tự động rút gọn khi văn bản dài (theo BR02). | "Dữ liệu chủ đề không hợp lệ" |
| VR03 | Trạng thái chủ đề | • Chủ đề chỉ hiển thị khi ở trạng thái Đang hoạt động (Active).• Toàn bộ người dùng Free và Premium đều xem được danh mục chủ đề (theo BR03). | "Dữ liệu chủ đề không hợp lệ" |

### **14.5. Quy tắc nghiệp vụ (Business Rules \- BR)**

| Mã BR | Tên quy tắc | Nội dung chi tiết |
| :---- | :---- | :---- |
| **BR01** | Tổ chức chủ đề độc lập | Danh mục chủ đề câu chuyện được phân chia theo các nhóm nội dung đời sống/văn hóa (Ẩm thực, Danh lam thắng cảnh, Ngụ ngôn, Giao tiếp đời sống...), không phân loại theo HSK ở cấp chủ đề. |
| **BR02** | Hiển thị thông tin tổng quan chủ đề | Mỗi chủ đề hiển thị tên chủ đề, hình ảnh minh họa, tổng số lượng bài đọc và đoạn văn bản mô tả tóm tắt danh sách các câu chuyện bên trong (tự động rút gọn hiển thị nếu văn bản dài). |
| **BR03** | Quyền truy cập danh mục chủ đề | Toàn bộ người dùng (tài khoản Free và Premium) đều được xem danh sách tất cả các chủ đề bài đọc; việc phân quyền sẽ áp dụng chi tiết ở cấp bài đọc. |

## **15\. Chức năng Xem danh sách câu chuyện theo chủ đề (STORY-02: View Stories by Topic)**

### **15.1. Thông tin chung**

| Mã chức năng | STORY-02 |
| :---- | :---- |
| **Tên chức năng** | Xem danh sách câu chuyện theo chủ đề (View Stories by Topic) |
| **Tác nhân (Actor)** | Người học (Learner) |
| **Mục tiêu** | Hiển thị danh sách các câu chuyện thuộc chủ đề đã chọn kèm phân loại HSK và điểm luyện tập lần trước |
| **Tiền điều kiện (Pre-condition)** | Người dùng đã chọn 1 chủ đề từ STORY-01 |
| **Hậu điều kiện (Post-condition)** | Hiển thị danh sách câu chuyện để người dùng chọn bài đọc |

### **15.2. Luồng chính (Main Flow)**

| Bước | Tác nhân | Hành động |
| :---- | :---- | :---- |
| **1** | Người dùng | Bấm chọn 1 chủ đề bài đọc. |
| **2** | Hệ thống | Lấy danh sách các câu chuyện thuộc chủ đề kèm lịch sử điểm số luyện tập của người dùng. |
| **3** | Hệ thống | Hiển thị danh sách câu chuyện gồm: Tiêu đề chữ Hán, Tiêu đề tiếng Việt, Phân loại cấp độ HSK, Thời lượng đọc ước tính và Điểm luyện tập đọc hiểu của lần làm trước (nếu có). |
| **4** | Người dùng | Bấm chọn 1 câu chuyện muốn đọc. |
| **5** | Hệ thống | Kiểm tra quyền truy cập bài đọc và điều hướng sang màn hình Đọc nội dung câu chuyện (STORY-03). |
| **6** | \- | Use case kết thúc. |

### **15.3. Luồng rẽ nhánh & Ngoại lệ (Alternative & Exception Flows)**

| Mã luồng | Tại bước | Tác nhân | Điều kiện & Hành động xử lý |
| :---- | :---- | :---- | :---- |
| **AF-01** | Bước 2 | Hệ thống | Lỗi tải dữ liệu: Thông báo "Không thể tải danh sách câu chuyện. Vui lòng thử lại". |
| **AF-02** | Bước 3 | Hệ thống | Chưa có bài đọc: Thông báo "Chủ đề này hiện chưa có bài đọc nào". |
| **AF-03** | Bước 3 | Người dùng | Quay lại danh sách chủ đề: Người dùng bấm nút Quay lại (Back ←) ➔ Hệ thống điều hướng quay về màn hình Danh sách chủ đề (STORY-01). |
| **AF-04** | Bước 5 | Hệ thống | Bài đọc yêu cầu tài khoản Premium: Người dùng Free chọn bài đọc HSK 4-6 ➔ Hiển thị thông báo: "Bài đọc này dành riêng cho tài khoản Premium" kèm tùy chọn nâng cấp gói. |

### **15.4. Ràng buộc dữ liệu (Validation Rules \- VR)**

| Mã VR | Trường dữ liệu | Ràng buộc kiểm tra | Thông báo lỗi hiển thị |
| :---- | :---- | :---- | :---- |
| **VR01** | Cấp độ HSK bài đọc | Bắt buộc thuộc một trong các cấp độ: HSK 1, HSK 2, HSK 3, HSK 4, HSK 5, HSK 6\. | "Cấp độ HSK không hợp lệ" |
| **VR02** | Thông tin bài đọc | Bắt buộc có đầy đủ Tiêu đề tiếng Trung, Tiêu đề tiếng Việt và Thời lượng đọc. | "Dữ liệu bài đọc không hợp lệ" |

### **15.5. Quy tắc nghiệp vụ (Business Rules \- BR)**

| Mã BR | Tên quy tắc | Nội dung chi tiết |
| :---- | :---- | :---- |
| **BR01** | Phân loại HSK theo từng bài đọc | Mỗi câu chuyện được gắn nhãn cấp độ HSK tương ứng với độ khó của từ vựng và cấu trúc ngữ pháp trong bài đọc đó. |
| **BR02** | Hiển thị điểm luyện tập gần nhất | Nếu người dùng đã từng làm bài luyện tập đọc hiểu của câu chuyện, hệ thống hiển thị điểm số đạt được của lần gần nhất (ví dụ: Điểm: 80/100). Nếu chưa từng làm bài tập, hiển thị trạng thái chưa có điểm (Chưa luyện tập). |
| **BR03** | Tính toán thời lượng đọc ước tính | Thời lượng đọc được tự động tính toán dựa trên tổng số lượng chữ Hán trong bài và tốc độ đọc trung bình theo từng cấp độ HSK (HSK 1-2: 60-80 chữ/phút, HSK 3-4: 100-120 chữ/phút, HSK 5-6: 140-160 chữ/phút). |
| **BR04** | Phân quyền truy cập bài đọc theo HSK | • Tài khoản Miễn phí (Free): Được mở khóa và đọc toàn bộ các bài đọc thuộc cấp độ HSK 1, HSK 2 và HSK 3\.• Tài khoản Trả phí (Premium): Được mở khóa toàn bộ bài đọc từ HSK 1 đến HSK 6\. |

## **16\. Chức năng Đọc nội dung câu chuyện (STORY-03: Read Story Content)**

### **16.1. Thông tin chung**

| Mã chức năng | STORY-03 |
| :---- | :---- |
| **Tên chức năng** | Đọc nội dung câu chuyện (Read Story Content) |
| **Tác nhân (Actor)** | Người học (Learner) |
| **Mục tiêu** | Hiển thị toàn văn nội dung câu chuyện bằng tiếng Trung để người dùng luyện đọc |
| **Tiền điều kiện (Pre-condition)** | Người dùng chọn 1 câu chuyện từ STORY-02 |
| **Hậu điều kiện (Post-condition)** | Người dùng đọc xong nội dung câu chuyện |

### **16.2. Luồng chính (Main Flow)**

| Bước | Tác nhân | Hành động |
| :---- | :---- | :---- |
| **1** | Người dùng | Bấm chọn 1 câu chuyện từ danh sách bài đọc. |
| **2** | Hệ thống | Kiểm tra quyền truy cập tài khoản và truy xuất dữ liệu câu chuyện (đoạn văn tiếng Trung, tệp âm thanh, bản dịch đoạn văn, từ vựng và câu hỏi đọc hiểu). |
| **3** | Hệ thống | Hiển thị nội dung toàn bộ câu chuyện bằng tiếng Trung theo từng đoạn văn. |
| **4** | Người dùng | Đọc nội dung câu chuyện. |
| **5** | Người dùng | Hoàn thành bài đọc và quay lại danh sách. |
| **6** | \- | Use case kết thúc. |

### **16.3. Luồng rẽ nhánh & Ngoại lệ (Alternative & Exception Flows)**

| Mã luồng | Tại bước | Tác nhân | Điều kiện & Hành động xử lý |
| :---- | :---- | :---- | :---- |
| **AF-01** | Bước 2 | Hệ thống | Bài đọc Premium bị khóa đối với tài khoản Free:• Tài khoản Free truy cập vào bài đọc Premium (HSK 4 đến HSK 6\) ➔ Hệ thống hiển thị trạng thái khóa nội dung kèm dòng chữ yêu cầu nâng cấp tài khoản Premium.• Dừng Use case. |
| **AF-02** | Bước 3 | Người dùng | Nghe audio câu chuyện: Người dùng bấm nút Play ➔ Chuyển sang màn hình Nghe audio câu chuyện (STORY-04). |
| **AF-03** | Bước 3 | Người dùng | Xem bản dịch tiếng Việt: Người dùng bấm nút Hiện dịch ➔ Chuyển sang màn hình Xem bản dịch câu chuyện (STORY-05). |
| **AF-04** | Bước 4 | Người dùng | Tra từ & Lưu Flashcard: Người dùng bấm vào 1 từ chưa biết ➔ Chuyển sang màn hình Tra từ & Lưu Flashcard (STORY-06). |
| **AF-05** | Bước 4 | Người dùng | Luyện tập đọc hiểu: Người dùng bấm nút Luyện tập ➔ Chuyển sang màn hình Luyện tập đọc hiểu câu chuyện (STORY-07). |
| **AF-06** | Bước 3 | Người dùng | Quay lại danh sách câu chuyện: Người dùng bấm nút Quay lại (Back ←) ➔ Hệ thống điều hướng quay về màn hình Danh sách câu chuyện (STORY-02). |
| **EF-01** | Bước 2 | Hệ thống | Lỗi tải bài đọc: Thông báo "Không thể tải nội dung câu chuyện. Vui lòng thử lại". |

### **16.4. Ràng buộc dữ liệu (Validation Rules \- VR)**

| Mã VR | Trường dữ liệu | Ràng buộc kiểm tra | Thông báo lỗi hiển thị |
| :---- | :---- | :---- | :---- |
| **VR01** | Nội dung văn bản | Bắt buộc có văn bản tiếng Trung đầy đủ được chia theo các đoạn văn. | "Nội dung bài đọc không hợp lệ" |
| **VR02** | Gói tài khoản người dùng | Thuộc loại hợp lệ: FREE hoặc PREMIUM. | "Thông tin gói tài khoản không hợp lệ" |

### **16.5. Quy tắc nghiệp vụ (Business Rules \- BR)**

| Mã BR | Tên quy tắc | Nội dung chi tiết |
| :---- | :---- | :---- |
| **BR01** | Cấu trúc hiển thị theo đoạn văn | Nội dung câu chuyện được chia thành các đoạn văn riêng biệt; mỗi câu/đoạn có mã định danh để đồng bộ với âm thanh và bản dịch. |
| **BR02** | Cơ chế tương tác chạm từ vựng (Click-to-Lookup) | Người dùng có thể chạm vào bất kỳ từ tiếng Trung nào trong bài đọc để tra cứu nghĩa nhanh mà không làm gián đoạn việc đọc. |
| **BR03** | Không ràng buộc thứ tự đọc | Người dùng được tự do chọn đọc bất kỳ câu chuyện nào mở khóa trong chủ đề mà không bị ép buộc đọc tuần tự. |
| **BR04** | Quy tắc khóa nội dung Premium | Bài đọc cấp độ HSK 4, HSK 5, HSK 6 tự động ở trạng thái Khóa đối với người dùng tài khoản Free; hệ thống chặn toàn bộ thao tác đọc, nghe audio, tra từ và luyện tập cho đến khi người dùng nâng cấp gói Premium. |

## **17\. Chức năng Nghe audio câu chuyện (STORY-04: Listen to Story Audio)**

### **17.1. Thông tin chung**

| Mã chức năng | STORY-04 |
| :---- | :---- |
| **Tên chức năng** | Nghe audio câu chuyện (Listen to Story Audio) |
| **Tác nhân (Actor)** | Người học (Learner) |
| **Mục tiêu** | Phát âm thanh giọng đọc bản xứ của câu chuyện kèm hiệu ứng làm nổi bật đoạn đang đọc |
| **Tiền điều kiện (Pre-condition)** | Người dùng đang ở màn hình đọc câu chuyện (STORY-03) |
| **Hậu điều kiện (Post-condition)** | Hệ thống phát âm thanh theo yêu cầu của người dùng |

### **17.2. Luồng chính (Main Flow)**

| Bước | Tác nhân | Hành động |
| :---- | :---- | :---- |
| **1** | Người dùng | Bấm nút Phát âm thanh (Play). |
| **2** | Hệ thống | Tải tệp âm thanh và phát bài đọc chuẩn giọng bản xứ. |
| **3** | Hệ thống | Tự động làm nổi bật (Highlight) đoạn văn bản tương ứng với giọng đọc theo thời gian thực. |
| **4** | Người dùng | Nghe audio bài đọc (có thể tạm dừng hoặc tiếp tục). |
| **5** | \- | Use case kết thúc. |

### **17.3. Luồng rẽ nhánh & Ngoại lệ (Alternative & Exception Flows)**

| Mã luồng | Tại bước | Tác nhân | Điều kiện & Hành động xử lý |
| :---- | :---- | :---- | :---- |
| **AF-01** | Bước 4 | Người dùng | Tạm dừng phát: Người dùng bấm Pause ➔ Hệ thống tạm dừng phát âm thanh tại vị trí hiện tại. |
| **EF-01** | Bước 2 | Hệ thống | Lỗi tệp âm thanh: Không tải được audio ➔ Thông báo: "Không thể phát âm thanh câu chuyện. Vui lòng thử lại". |

### **17.4. Ràng buộc dữ liệu (Validation Rules \- VR)**

| Mã VR | Trường dữ liệu | Ràng buộc kiểm tra | Thông báo lỗi hiển thị |
| :---- | :---- | :---- | :---- |
| **VR01** | Tệp âm thanh | Tệp âm thanh định dạng chuẩn (.mp3 / .aac) bắt buộc tồn tại và khớp với văn bản câu chuyện. | "Tệp âm thanh không hợp lệ" |

### **17.5. Quy tắc nghiệp vụ (Business Rules \- BR)**

| Mã BR | Tên quy tắc | Nội dung chi tiết |
| :---- | :---- | :---- |
| **BR01** | Đồng bộ giọng đọc và văn bản (Real-time Highlighting) | Hệ thống tự động làm nổi bật đoạn văn/câu tiếng Trung tương ứng theo mốc thời gian thực của giọng đọc audio. |
| **BR02** | Tự động cuộn trang theo tiến trình nghe (Auto-scroll) | Màn hình tự động cuộn theo tiến trình phát audio để đoạn văn đang được đọc luôn nằm trong vùng nhìn thấy của người dùng. |
| **BR03** | Điều khiển âm thanh linh hoạt | Người dùng có thể tạm dừng, phát tiếp hoặc kéo thanh tiến độ phát đến vị trí đoạn văn mong muốn. |
| **BR04** | Phát âm thanh không bị ngắt khi tra từ | Âm thanh bài đọc vẫn duy trì khi người dùng mở khung tra từ vựng nhanh (trừ trường hợp người dùng chủ động bấm nghe phát âm riêng của từ đó). |

## **18\. Chức năng Xem bản dịch câu chuyện (STORY-05: View Story Translation)**

### **18.1. Thông tin chung**

| Mã chức năng | STORY-05 |
| :---- | :---- |
| **Tên chức năng** | Xem bản dịch câu chuyện (View Story Translation) |
| **Tác nhân (Actor)** | Người học (Learner) |
| **Mục tiêu** | Hiển thị hoặc ẩn đoạn dịch tiếng Việt ngay dưới từng đoạn tiếng Trung tương ứng |
| **Tiền điều kiện (Pre-condition)** | Người dùng đang ở màn hình đọc câu chuyện (STORY-03) |
| **Hậu điều kiện (Post-condition)** | Đoạn dịch tiếng Việt được hiển thị ngay bên dưới đoạn tiếng Trung tương ứng |

### **18.2. Luồng chính (Main Flow)**

| Bước | Tác nhân | Hành động |
| :---- | :---- | :---- |
| **1** | Người dùng | Bấm nút "Hiện dịch". |
| **2** | Hệ thống | Hiển thị đoạn dịch nghĩa tiếng Việt ngay bên dưới đoạn tiếng Trung tương ứng. |
| **3** | Người dùng | Đọc đối chiếu nội dung bản dịch với đoạn tiếng Trung. |
| **4** | Người dùng | Bấm nút "Ẩn dịch" để ẩn phần dịch khi không cần thiết. |
| **5** | \- | Use case kết thúc. |

### **18.3. Luồng rẽ nhánh & Ngoại lệ (Alternative & Exception Flows)**

| Mã luồng | Tại bước | Tác nhân | Điều kiện & Hành động xử lý |
| :---- | :---- | :---- | :---- |
| **AF-01** | Bước 2 | Hệ thống | Đoạn chưa có bản dịch: Hiển thị ghi chú "Bản dịch đoạn này đang được cập nhật". |

### **18.4. Ràng buộc dữ liệu (Validation Rules \- VR)**

| Mã VR | Trường dữ liệu | Ràng buộc kiểm tra | Thông báo lỗi hiển thị |
| :---- | :---- | :---- | :---- |
| **VR01** | Dữ liệu bản dịch | Mỗi đoạn tiếng Trung phải có bản dịch tiếng Việt tương ứng theo mã đoạn. | "Dữ liệu bản dịch không hợp lệ" |

### **18.5. Quy tắc nghiệp vụ (Business Rules \- BR)**

| Mã BR | Tên quy tắc | Nội dung chi tiết |
| :---- | :---- | :---- |
| **BR01** | Hiển thị bản dịch theo khối đoạn song ngữ | Bản dịch tiếng Việt được bố trí hiển thị ngay bên dưới đoạn văn tiếng Trung tương ứng để người dùng đối chiếu nghĩa chuẩn xác theo từng đoạn. |
| **BR02** | Chuyển đổi trạng thái hiển thị tức thì | Việc Bật hoặc Ẩn bản dịch diễn ra ngay lập tức trên màn hình bài đọc mà không cần tải lại trang. |
| **BR03** | Ghi nhớ tùy chọn hiển thị bản dịch | Hệ thống ghi nhớ trạng thái (Bật hoặc Ẩn dịch) gần nhất của người dùng để áp dụng cho các lần đọc tiếp theo. |

## **19\. Chức năng Tra từ & Lưu Flashcard trong câu chuyện (STORY-06: Inline Vocabulary Lookup & Save Flashcard)**

### **19.1. Thông tin chung**

| Mã chức năng | STORY-06 |
| :---- | :---- |
| **Tên chức năng** | Tra từ & Lưu Flashcard trong câu chuyện (Inline Vocabulary Lookup & Save Flashcard) |
| **Tác nhân (Actor)** | Người học (Learner) |
| **Mục tiêu** | Tra cứu nhanh thông tin từ vựng khi đang đọc và lưu từ vào bộ thẻ Flashcard "Sổ từ đã lưu" |
| **Tiền điều kiện (Pre-condition)** | Người dùng đang đọc câu chuyện (STORY-03) và bấm vào 1 từ tiếng Trung |
| **Hậu điều kiện (Post-condition)** | Hiển thị thông tin từ vựng và lưu từ vào bộ Flashcard của người dùng |

### **19.2. Luồng chính (Main Flow)**

| Bước | Tác nhân | Hành động |
| :---- | :---- | :---- |
| **1** | Người dùng | Chạm/bấm vào một từ tiếng Trung trong câu chuyện. |
| **2** | Hệ thống | Hiển thị khung giải nghĩa gồm: Chữ Hán, Pinyin, Nút phát âm thanh, Nghĩa tiếng Việt và nút "Lưu Flashcard". |
| **3** | Người dùng | Bấm nút phát âm thanh để nghe đọc từ (nếu cần). |
| **4** | Người dùng | Bấm nút "Lưu Flashcard". |
| **5** | Hệ thống | Lưu từ vựng vào bộ thẻ Flashcard thuộc chủ đề "Sổ từ đã lưu" của người dùng và thông báo: "Đã lưu từ vào bộ thẻ Flashcard". |
| **6** | Người dùng | Đóng khung giải nghĩa và tiếp tục đọc câu chuyện. |
| **7** | \- | Use case kết thúc. |

### **19.3. Luồng rẽ nhánh & Ngoại lệ (Alternative & Exception Flows)**

| Mã luồng | Tại bước | Tác nhân | Điều kiện & Hành động xử lý |
| :---- | :---- | :---- | :---- |
| **AF-01** | Bước 4 | Hệ thống | Từ vựng đã có trong Flashcard: Nếu người dùng bấm lại nút Lưu Flashcard, hệ thống vẫn duy trì lưu từ đó 1 lần trong bộ thẻ (không bị trùng lặp và không hủy lưu). |
| **EF-01** | Bước 2 | Hệ thống | Từ không có trong từ điển: Hiển thị nghĩa dịch ngữ cảnh tự động từ hệ thống. |

### **19.4. Ràng buộc dữ liệu (Validation Rules \- VR)**

| Mã VR | Trường dữ liệu | Ràng buộc kiểm tra | Thông báo lỗi hiển thị |
| :---- | :---- | :---- | :---- |
| **VR01** | Dữ liệu từ tra cứu | Bắt buộc có Chữ Hán, Pinyin, Tệp âm thanh phát âm và Nghĩa tiếng Việt. | "Dữ liệu từ vựng không hợp lệ" |

### **19.5. Quy tắc nghiệp vụ (Business Rules \- BR)**

| Mã BR | Tên quy tắc | Nội dung chi tiết |
| :---- | :---- | :---- |
| **BR01** | Tự động nhận diện từ ghép (Word Segmentation) | Khi chạm vào chữ Hán trong văn bản, hệ thống tự động nhận diện từ ghép hoàn chỉnh (2-4 chữ) theo ngữ cảnh câu chuyện để tra cứu chính xác nghĩa. |
| **BR02** | Đồng bộ tự động vào bộ thẻ Flashcard | Từ vựng đã lưu tự động vào bộ thẻ "Sổ từ đã lưu" (FLASHCARD-01) để ôn tập. |
| **BR03** | Quy tắc chống trùng lặp từ vựng | Mỗi từ vựng chỉ lưu duy nhất 1 bản ghi trong bộ thẻ Flashcard của người dùng; bấm lưu nhiều lần không tạo thêm bản ghi trùng lặp. |
| **BR04** | Tra cứu hoàn toàn miễn phí | Hoạt động tra cứu từ vựng và lưu Flashcard trong bài đọc không tiêu hao Năng lượng (Energy) của người dùng. |

## **20\. Chức năng Luyện tập đọc hiểu câu chuyện (STORY-07: Story Reading Comprehension Quiz)**

### **20.1. Thông tin chung**

| Mã chức năng | STORY-07 |
| :---- | :---- |
| **Tên chức năng** | Luyện tập đọc hiểu câu chuyện (Story Reading Comprehension Quiz) |
| **Tác nhân (Actor)** | Người học (Learner) |
| **Mục tiêu** | Đánh giá mức độ hiểu câu chuyện qua bộ câu hỏi trắc nghiệm, đúng/sai và điền từ kèm giải thích chi tiết |
| **Tiền điều kiện (Pre-condition)** | Người dùng đang đọc câu chuyện (STORY-03) và bấm nút "Luyện tập đọc hiểu" |
| **Hậu điều kiện (Post-condition)** | Ghi nhận kết quả, tính điểm, lưu điểm số vào lịch sử bài đọc và cộng điểm XP |

### **20.2. Luồng chính (Main Flow)**

| Bước | Tác nhân | Hành động |
| :---- | :---- | :---- |
| **1** | Người dùng | Bấm nút "Luyện tập đọc hiểu" tại bài đọc. |
| **2** | Hệ thống | Truy xuất và hiển thị danh sách các câu hỏi đọc hiểu gồm: Trắc nghiệm chọn đáp án, Câu hỏi Đúng/Sai, và Câu hỏi Điền từ. |
| **3** | Người dùng | Làm bài và chọn/nhập đáp án cho từng câu hỏi. |
| **4** | Người dùng | Bấm "Kiểm tra đáp án". |
| **5** | Hệ thống | Đối chiếu và hiển thị kết quả Đúng/Sai cùng lời giải thích chi tiết. |
| **6** | Người dùng | Hoàn thành tất cả các câu hỏi và bấm "Hoàn thành". |
| **7** | Hệ thống | Hiển thị màn hình tổng kết điểm số, lưu điểm số đạt được vào lịch sử bài đọc và cộng điểm XP thưởng khi người dùng đạt từ 70/100 điểm trở lên (theo BR06). |
| **8** | \- | Use case kết thúc. |

### **20.3. Luồng rẽ nhánh & Ngoại lệ (Alternative & Exception Flows)**

| Mã luồng | Tại bước | Tác nhân | Điều kiện & Hành động xử lý |
| :---- | :---- | :---- | :---- |
| **AF-01** | Bước 5 | Hệ thống | Trả lời sai: Đánh dấu đáp án sai, hiển thị đáp án đúng kèm đoạn văn giải thích trong bài đọc để người dùng đối chiếu. |
| **AF-02** | Bước 3 | Hệ thống | Chưa hoàn thành câu hỏi: Nút kiểm tra ở trạng thái vô hiệu hóa khi người dùng chưa chọn hoặc chưa nhập đáp án. |
| **AF-03** | Bước 3 | Người dùng | Thoát khi đang làm bài tập:• Người dùng bấm nút Quay lại (Back ←) khi bài tập chưa hoàn thành ➔ Hệ thống hiển thị hộp thoại xác nhận: "Bạn có chắc muốn thoát? Kết quả bài làm hiện tại sẽ không được lưu".• Nếu người dùng chọn "Thoát" ➔ Hệ thống hủy phiên làm bài, không ghi nhận kết quả điểm số và quay về màn hình đọc câu chuyện (STORY-03).• Nếu người dùng chọn "Ở lại" ➔ Tiếp tục làm bài tập. |

### **20.4. Ràng buộc dữ liệu (Validation Rules \- VR)**

| Mã VR | Trường dữ liệu | Ràng buộc kiểm tra | Thông báo lỗi hiển thị |
| :---- | :---- | :---- | :---- |
| **VR01** | Bộ câu hỏi đọc hiểu | Bao gồm 3 dạng câu hỏi hợp lệ: Trắc nghiệm (4 lựa chọn), Đúng/Sai (2 lựa chọn), hoặc Điền từ vào chỗ trống. | "Dữ liệu câu hỏi đọc hiểu không hợp lệ" |
| **VR02** | Đáp án & Giải thích | Mỗi câu hỏi bắt buộc có đáp án chuẩn xác kèm nội dung giải thích chi tiết. | "Dữ liệu đáp án không hợp lệ" |
| **VR03** | Thang điểm | Điểm số tính theo thang điểm 100 (0 ≤ Điểm ≤ 100). | "Điểm số không hợp lệ" |

### **20.5. Quy tắc nghiệp vụ (Business Rules \- BR)**

| Mã BR | Tên quy tắc | Nội dung chi tiết |
| :---- | :---- | :---- |
| **BR01** | Cơ cấu dạng bài đọc hiểu đa dạng | Bộ câu hỏi đọc hiểu được thiết kế linh hoạt gồm 3 dạng bài tập: (1) Trắc nghiệm chọn đáp án A/B/C/D, (2) Câu hỏi Đúng/Sai (True/False), (3) Điền từ thích hợp vào chỗ trống theo ngữ cảnh câu chuyện. |
| **BR02** | Công thức tính điểm số bài tập | Điểm số được tính tự động theo công thức: Điểm \= (Số câu trả lời đúng / Tổng số câu hỏi) × 100\. |
| **BR03** | Lưu và cập nhật điểm số vào danh sách bài đọc | Điểm số đạt được của lần luyện tập gần nhất sẽ được ghi đè và hiển thị ngay trên danh sách bài đọc (STORY-02) để người dùng dễ dàng theo dõi tiến độ. |
| **BR04** | Trích dẫn minh chứng giải thích | Mọi câu hỏi khi hiển thị kết quả Đúng/Sai đều trích dẫn chính xác đoạn văn trong câu chuyện chứa thông tin giải đáp để người học hiểu sâu bản chất. |
| **BR05** | Không giới hạn lượt luyện tập lại | Người dùng có thể làm lại bài luyện tập đọc hiểu không giới hạn số lần để nâng cao điểm số và củng cố kiến thức. |
| **BR06** | Quy tắc cộng điểm kinh nghiệm (XP) | Điểm XP được cộng khi đạt từ 70/100 điểm trở lên; mức XP thưởng tương ứng cấp độ HSK của bài đọc. |
| **BR07** | Quy tắc hủy kết quả khi thoát giữa chừng | Nếu người dùng thoát bài tập trước khi bấm "Hoàn thành", hệ thống sẽ không ghi nhận điểm số của phiên làm bài dở dang và giữ nguyên điểm số của lần làm bài thành công trước đó (nếu có). |

# **PHÂN HỆ V. TRUNG TÂM LUYỆN THI HSK (HSK EXAM CENTER)**

## **1\. Chức năng Xem danh sách bài thi HSK (HSK-01: View HSK Exam Levels)**

### **1.1. Thông tin chung**

| Mã chức năng | HSK-01 |
| :---- | :---- |
| **Tên chức năng** | Xem danh sách bài thi HSK (View HSK Exam Levels) |
| **Tác nhân (Actor)** | Người học (Learner) |
| **Mục tiêu** | Xem danh sách các cấp độ thi từ HSK 1 đến HSK 6 và số lượng đề thi có trong từng cấp độ |
| **Tiền điều kiện (Pre-condition)** | Người học đang ở màn hình Luyện tập và chọn mục "Luyện thi" |
| **Hậu điều kiện (Post-condition)** | Hiển thị danh sách các cấp độ HSK kèm số lượng đề thi và trạng thái mở khóa theo gói tài khoản |

### **1.2. Luồng chính (Main Flow)**

| Bước | Tác nhân | Hành động |
| :---- | :---- | :---- |
| **1** | Người học | Bấm chọn mục "Luyện thi" trên màn hình Luyện tập. |
| **2** | Hệ thống | Tải và hiển thị danh sách các cấp độ HSK (HSK 1, HSK 2, HSK 3, HSK 4, HSK 5, HSK 6\) kèm số lượng đề thi hiện có trong từng cấp độ (ví dụ: HSK 1: 10 đề thi, HSK 2: 12 đề thi,...) và trạng thái truy cập (Mở khóa hoặc Khóa Premium). |
| **3** | \- | Kết thúc chức năng. |

### **1.3. Luồng rẽ nhánh & Ngoại lệ (Alternative & Exception Flows)**

| Mã luồng | Điều kiện | Hành động xử lý |
| :---- | :---- | :---- |
| **AF-01** | Bấm chọn cấp độ đã mở khóa | Người học bấm vào cấp độ được phép truy cập ➔ Hệ thống chuyển sang Use Case HSK-02: Xem danh sách đề thi theo HSK. |
| **AF-02** | Bấm chọn cấp độ bị khóa (HSK 4, 5, 6 với tài khoản miễn phí) | Hệ thống hiển thị thông báo: "Vui lòng nâng cấp tài khoản Premium để mở khóa đề thi cấp độ này". |
| **AF-03** | Bấm nút Quay lại | Người học bấm nút "Quay lại" ➔ Đưa người học trở về màn hình Luyện tập. |
| **EF-01** | Chưa có dữ liệu cấp độ | Hệ thống hiển thị thông báo: "Chưa có dữ liệu bài thi HSK". |
| **EF-02** | Lỗi tải dữ liệu | Hệ thống hiển thị thông báo: "Không thể tải danh sách bài thi. Vui lòng thử lại" kèm nút "Thử lại". |

### **1.4. Ràng buộc dữ liệu (Validation Rules \- VR)**

| Mã VR | Trường dữ liệu | Ràng buộc | Thông báo lỗi |
| :---- | :---- | :---- | :---- |
| **VR01** | Trạng thái tài khoản | Tài khoản người học đang ở trạng thái hợp lệ. | "Phiên đăng nhập hết hạn" |
| **VR02** | Quyền xem cấp độ | Phân quyền truy cập dựa trên loại gói tài khoản của người học. | "Nội dung yêu cầu tài khoản Premium" |

### **1.5. Quy tắc nghiệp vụ (Business Rules \- BR)**

| Mã BR | Tên quy tắc | Nội dung |
| :---- | :---- | :---- |
| **BR01** | Phân quyền theo gói tài khoản | • Tài khoản Miễn phí (Free): Được mở khóa và vào thi các cấp độ HSK 1, HSK 2, HSK 3; các cấp độ HSK 4, HSK 5, HSK 6 hiển thị biểu tượng Khóa kèm nhãn "Premium".• Tài khoản Trả phí (Premium): Được mở khóa toàn bộ tất cả các cấp độ từ HSK 1 đến HSK 6\. |
| **BR02** | Thông tin hiển thị mỗi cấp độ | Mỗi thẻ cấp độ HSK hiển thị rõ Tên cấp độ (HSK 1, HSK 2,...) và tổng số lượng đề thi hiện có trong cấp độ đó (không hiển thị điểm số trên thẻ cấp độ). |
| **BR03** | Thứ tự sắp xếp | Danh sách cấp độ luôn được sắp xếp theo thứ tự tăng dần từ HSK 1 đến HSK 6\. |

## **2\. Chức năng Xem danh sách đề thi theo HSK (HSK-02: View HSK Exam List by Level)**

### **2.1. Thông tin chung**

| Mã chức năng | HSK-02 |
| :---- | :---- |
| **Tên chức năng** | Xem danh sách đề thi theo HSK (View HSK Exam List by Level) |
| **Tác nhân (Actor)** | Người học (Learner) |
| **Mục tiêu** | Xem danh sách các bộ đề thi thử thuộc một cấp độ HSK cụ thể để lựa chọn đề thi bắt đầu làm bài |
| **Tiền điều kiện (Pre-condition)** | Người học đang ở màn hình Danh sách bài thi HSK (HSK-01) và chọn một cấp độ HSK hợp lệ (đã mở khóa) |
| **Hậu điều kiện (Post-condition)** | Hiển thị danh sách các đề thi với đầy đủ thông tin tên đề, thời gian làm bài |

### **2.2. Luồng chính (Main Flow)**

| Bước | Tác nhân | Hành động |
| :---- | :---- | :---- |
| **1** | Người học | Bấm chọn một cấp độ HSK đã mở khóa (ví dụ: HSK 2\) trên màn hình HSK-01. |
| **2** | Hệ thống | Tải và hiển thị danh sách các đề thi thuộc cấp độ đã chọn gồm:• Tên/Số thứ tự đề: "Đề thi số 1", "Đề thi số 2",...• Thời gian làm bài quy định: "... phút"• Tổng số câu hỏi trong đề. |
| **3** | \- | Kết thúc chức năng. |

### **2.3. Luồng rẽ nhánh & Ngoại lệ (Alternative & Exception Flows)**

| Mã luồng | Điều kiện | Hành động xử lý |
| :---- | :---- | :---- |
| **AF-01** | Bấm chọn một đề thi | Người học bấm vào một đề thi cụ thể ➔ Hệ thống chuyển sang màn hình làm bài thi HSK (bắt đầu từ câu hỏi đầu tiên của đề). |
| **AF-02** | Bấm nút Quay lại | Người học bấm nút "Quay lại" ➔ Đưa người học trở về màn hình Danh sách bài thi HSK (HSK-01). |
| **EF-01** | Cấp độ chưa có đề thi | Nếu cấp độ vừa chọn chưa có đề thi nào ➔ Hiển thị thông báo: "Hiện chưa có đề thi nào cho cấp độ này" kèm nút "Quay lại". |
| **EF-02** | Lỗi tải dữ liệu | Hệ thống hiển thị thông báo: "Không thể tải danh sách đề thi. Vui lòng thử lại" kèm nút "Thử lại". |
| AF-03 | Không đủ Energy | Người học bấm chọn một đề thi nhưng Energy khả dụng nhỏ hơn 30 ➔ Hệ thống không bắt đầu bài thi và hiển thị thông báo: "Bạn không đủ năng lượng. Vui lòng nạp thêm hoặc chờ hồi phục". |

### **2.4. Ràng buộc dữ liệu (Validation Rules \- VR)**

| Mã VR | Trường dữ liệu | Ràng buộc | Thông báo lỗi |
| :---- | :---- | :---- | :---- |
| **VR01** | Cấp độ HSK | Cấp độ được chọn phải nằm trong danh sách được phép truy cập của tài khoản. | "Bạn chưa có quyền truy cập cấp độ này" |
| **VR02** | Thời gian thi | Thời gian làm bài hiển thị là số nguyên dương tính theo phút. | "Dữ liệu thời gian không hợp lệ" |
| VR03 | Năng lượng khả dụng | Người học phải có tối thiểu 30 Energy trước khi bắt đầu làm bài thi. | "Bạn không đủ năng lượng. Vui lòng nạp thêm hoặc chờ hồi phục" |

### **2.5. Quy tắc nghiệp vụ (Business Rules \- BR)**

| Mã BR | Tên quy tắc | Nội dung |
| :---- | :---- | :---- |
| **BR01** | Thông tin bắt buộc trên mỗi thẻ đề | Mỗi thẻ đề thi phải thể hiện rõ ràng: Tên thứ tự đề ("Đề thi số ..."), Thời gian làm bài quy định (tính theo phút) và tổng số câu hỏi có trong đề. |
| **BR02** | Chuẩn thời gian theo cấp độ | Thời gian làm bài của mỗi đề thi được thiết lập chuẩn theo từng cấp độ HSK tương ứng (ví dụ: HSK 1: \~40 phút, HSK 2: \~55 phút, HSK 3: \~90 phút, HSK 4: \~105 phút, HSK 5: \~125 phút, HSK 6: \~140 phút). |
| **BR03** | Sắp xếp đề thi | Danh sách đề thi được sắp xếp tuần tự theo số thứ tự (Đề thi số 1, Đề thi số 2, Đề thi số 3,...). |
| BR04 | Tiêu hao Energy khi bắt đầu bài thi HSK | Bắt đầu đề thi HSK: trừ 30 Energy (theo HOME-03 BR02). Nếu Energy \< 30, chặn bắt đầu bài thi (xem VR03, AF-03). |

## **3\. Chức năng Luyện thi HSK \- Nghe phán đoán Đúng / Sai (HSK-LISTEN-TF: Listening True/False Picture Quiz)**

### **3.1. Thông tin chung**

| Mã chức năng | HSK-LISTEN-TF |
| :---- | :---- |
| **Tên chức năng** | Nghe phán đoán Đúng / Sai (Listening True/False Picture Quiz) |
| **Tác nhân (Actor)** | Người học (Learner) |
| **Mục tiêu** | Nghe âm thanh và nhìn hình ảnh để chọn đáp án Đúng hoặc Sai trong đề thi |
| **Tiền điều kiện (Pre-condition)** | Người học đang làm đề thi HSK và ở câu hỏi dạng Nghe phán đoán Đúng / Sai |
| **Hậu điều kiện (Post-condition)** | Ghi nhận đáp án đã chọn vào bài thi (kết quả chấm điểm sẽ hiển thị sau khi nộp bài) |

### **3.2. Luồng chính (Main Flow)**

| Bước | Tác nhân | Hành động |
| :---- | :---- | :---- |
| **1** | Hệ thống | Hiển thị: 1 bức tranh, Nút phát âm thanh và 2 nút chọn: Đúng (✓) / Sai (✗). |
| **2** | Người học | Bấm nút phát âm thanh để nghe lại đoạn ghi âm (hệ thống đã tự động phát 2 lần liên tiếp khi vào câu; người học được nghe lại tối đa 2 lần, tổng tối đa 4 lần/câu). |
| **3** | Hệ thống | Tự động phát đoạn ghi âm 2 lần liên tiếp ngay khi vào câu. |
| **4** | Người học | Nhìn tranh, đối chiếu âm thanh và bấm chọn "Đúng" (khớp tranh) hoặc "Sai" (không khớp tranh). |
| **5** | Hệ thống | Ghi nhận lựa chọn, làm sáng nút đã chọn và chưa hiển thị kết quả đúng/sai. |
| **6** | \- | Kết thúc chức năng (hoặc chuyển sang câu tiếp theo). |

### **3.3. Luồng rẽ nhánh & Ngoại lệ (Alternative & Exception Flows)**

| Mã luồng | Điều kiện | Hành động xử lý |
| :---- | :---- | :---- |
| **AF-01** | Đổi đáp án | Người học bấm chọn lại giữa "Đúng" và "Sai" trước khi nộp bài. |
| **AF-02** | Nghe lại âm thanh | Bấm nút phát âm thanh để nghe lại đoạn đọc (hệ thống đã tự động phát 2 lần liên tiếp khi vào câu; cho phép nghe lại tối đa 2 lần, tổng tối đa 4 lần/câu). |
| **AF-03** | Bấm nút Quay lại | Hiển thị hộp thoại: "Dừng bài thi?" ➔ Nếu chọn thoát thì dừng và về danh sách đề thi. |
| **EF-01** | Lỗi âm thanh | Thông báo: "Không thể phát âm thanh. Vui lòng thử lại" kèm nút "Thử lại". |
| **EF-02** | Lỗi hình ảnh | Thông báo: "Không thể tải hình ảnh. Vui lòng thử lại" kèm nút "Thử lại". |

### **3.4. Ràng buộc dữ liệu (Validation Rules \- VR)**

| Mã VR | Trường dữ liệu | Ràng buộc dữ liệu | Thông báo hiển thị |
| :---- | :---- | :---- | :---- |
| VR01 | Lựa chọn đáp án | Bắt buộc chọn 1 trong 2 nút: Đúng hoặc Sai. | "Vui lòng chọn Đúng hoặc Sai" |
| VR02 | Tệp âm thanh | • File .mp3, .m4a, .aac, .wav (dung lượng ≤ 5MB, thời lượng 2–15s).• Giọng chuẩn, rõ tiếng; hệ thống tự động phát 2 lần liên tiếp khi vào câu, người học được bấm nghe lại tối đa 2 lần (tổng tối đa 4 lần/câu). | "Không thể phát âm thanh" |
| VR03 | Tệp hình ảnh | • Bắt buộc có đúng 01 ảnh minh họa. • File .jpg, .png, .webp (dung lượng ≤ 3MB, rõ nét). | "Không thể tải hình ảnh" |

### **3.5. Quy tắc nghiệp vụ (Business Rules \- BR)**

| Mã BR | Tên quy tắc | Nội dung |
| :---- | :---- | :---- |
| **BR01** | Số lần phát âm thanh | Tự động phát 2 lần liên tiếp khi vào câu; cho phép nghe lại tối đa 2 lần (tổng tối đa 4 lần/câu). |
| **BR02** | Tiêu chuẩn Đúng / Sai | • Chọn Đúng: Hình ảnh khớp với nội dung nghe.• Chọn Sai: Hình ảnh không khớp hoặc trái ngược nội dung nghe. |
| **BR03** | Ẩn kết quả lúc làm bài | Hệ thống không báo đúng/sai ngay sau khi chọn; chỉ công bố điểm số và kết quả chi tiết sau khi người học hoàn thành tất cả câu hỏi và nộp bài thi. |
| **BR04** | Lưu tiến độ | Đáp án tự động lưu lại khi chuyển qua lại giữa các câu hỏi trong đề. |

## **4\. Chức năng Luyện thi HSK \- Nghe câu đơn chọn 1 trong 3 tranh riêng biệt (HSK-LISTEN-PIC: Listening Single Sentence 3-Picture Quiz)**

### **4.1. Thông tin chung**

| Mã chức năng | HSK-LISTEN-PIC |
| :---- | :---- |
| Tên chức năng | Nghe câu đơn chọn 1 trong 3 tranh riêng biệt (Listening Single Sentence 3-Picture Quiz) |
| Tác nhân (Actor) | Người học (Learner) |
| Mục tiêu | Nghe 01 câu đơn tiếng Trung và chọn 1 trong 3 bức tranh riêng biệt của câu đó phù hợp với nội dung nghe. |
| Tiền điều kiện | Người học đang làm đề thi HSK và ở phần Nghe câu đơn chọn 3 tranh. |
| Hậu điều kiện | Ghi nhận đáp án đã chọn vào bài thi (kết quả hiển thị sau khi nộp bài). |

### **4.2. Luồng chính (Main Flow)**

| Bước | Tác nhân | Hành động |
| :---- | :---- | :---- |
| 1 | Hệ thống | Hiển thị: Nút phát âm thanh và 3 bức tranh riêng biệt của câu hỏi hiện tại (Tranh A, Tranh B, Tranh C). |
| 2 | Người học | Bấm nút phát âm thanh để nghe lại câu đơn (hệ thống đã tự động phát 2 lần liên tiếp khi vào câu; người học được nghe lại tối đa 2 lần, tổng tối đa 4 lần/câu). |
| 3 | Hệ thống | Tự động phát đoạn ghi âm 01 câu đơn tiếng Trung 02 lần liên tiếp ngay khi vào câu. |
| 4 | Người học | Quan sát 3 tranh của câu hỏi, đối chiếu nội dung câu đơn vừa nghe và bấm chọn 01 trong 3 tranh. |
| 5 | Hệ thống | Ghi nhận lựa chọn, làm sáng viền bức tranh đã chọn và chưa hiển thị kết quả đúng/sai. |
| 6 | \- | Kết thúc câu hỏi và chuyển sang câu tiếp theo (với 3 bức tranh mới). |

### **4.3. Luồng rẽ nhánh & Ngoại lệ (Alternative & Exception Flows)**

| Mã luồng | Điều kiện kích hoạt | Hành động xử lý |
| :---- | :---- | :---- |
| AF-01 | Đổi đáp án tranh | Người học bấm chọn bức tranh khác trong 3 tranh của câu hiện tại trước khi nộp bài. Hệ thống chuyển vùng chọn sang tranh mới. |
| AF-02 | Nghe lại âm thanh | Bấm nút phát âm thanh để nghe lại câu đơn (hệ thống đã tự động phát 2 lần liên tiếp khi vào câu; cho phép nghe lại tối đa 2 lần, tổng tối đa 4 lần/câu). |
| AF-03 | Bấm nút Quay lại | Hiển thị thông báo: "Dừng bài thi?" ➔ Nếu xác nhận thoát thì dừng làm bài và trở về màn hình danh sách đề thi. |
| EF-01 | Lỗi âm thanh | Thông báo: "Không thể phát âm thanh" kèm nút "Thử lại". |
| EF-02 | Lỗi tải tranh | Thông báo: "Lỗi tải danh sách hình ảnh" kèm nút "Thử lại". |

### **4.4. Ràng buộc dữ liệu (Validation Rules \- VR)**

| Mã VR | Trường dữ liệu | Ràng buộc dữ liệu | Thông báo hiển thị |
| :---- | :---- | :---- | :---- |
| VR01 | Lựa chọn đáp án | Bắt buộc chọn duy nhất 1 trong 3 tranh riêng biệt (A, B hoặc C) của câu hỏi. | "Vui lòng chọn 1 bức tranh" |
| VR02 | Tệp âm thanh câu đơn | • File .mp3, .m4a, .aac, .wav (dung lượng ≤ 5MB, thời lượng 2–15s). • Giọng đọc 01 câu đơn tiếng Trung rõ tiếng, chuẩn bản xứ; hệ thống tự động phát 2 lần liên tiếp khi vào câu, người học được bấm nghe lại tối đa 2 lần (tổng tối đa 4 lần/câu). | "Không thể phát âm thanh" |
| VR03 | Tệp 3 hình ảnh riêng biệt | • Bắt buộc có đủ 03 tệp hình ảnh độc lập cho câu hỏi (A, B, C). • File .jpg, .png, .webp (dung lượng ≤ 3MB/ảnh, rõ nét). | "Lỗi tải danh sách hình ảnh" |

### **4.5. Quy tắc nghiệp vụ (Business Rules \- BR)**

| Mã BR | Tên quy tắc | Nội dung quy định |
| :---- | :---- | :---- |
| BR01 | Số lần phát âm thanh | Tự động phát 2 lần liên tiếp khi vào câu; cho phép nghe lại tối đa 2 lần (tổng tối đa 4 lần/câu). |
| BR02 | Tranh độc lập theo từng câu | Mỗi câu hỏi sở hữu bộ 3 bức tranh riêng biệt; chuyển qua câu hỏi mới hệ thống sẽ nạp bộ 3 tranh mới hoàn toàn. |
| BR03 | Quy tắc chọn đơn | Người học chỉ được chọn duy nhất 1 trong 3 tranh cho mỗi câu hỏi. |
| BR04 | Ẩn kết quả lúc làm bài | Hệ thống không báo đúng/sai ngay; chỉ chấm điểm và hiển thị đáp án chi tiết sau khi người học nộp toàn bộ bài thi. |
| BR05 | Tự động lưu tiến độ | Bức tranh đã chọn được tự động lưu lại khi chuyển đổi giữa các câu hỏi trong đề thi. |

## **5\. Chức năng Luyện thi HSK \- Nghe hội thoại chọn 1 trong 6 tranh chung (HSK-LISTEN-6PIC: Listening Dialogue 6-Shared Picture Matching)**

### **5.1. Thông tin chung**

| Mã chức năng | HSK-LISTEN-6PIC |
| :---- | :---- |
| Tên chức năng | Nghe hội thoại chọn 1 trong 6 tranh chung (Listening Dialogue 6-Shared Picture Matching) |
| Tác nhân (Actor) | Người học (Learner) |
| Mục tiêu | Nghe đoạn hội thoại tiếng Trung của từng câu hỏi và chọn 1 bức tranh phù hợp trong ngân hàng 6 bức tranh chung cố định (A–F) của cụm 5 câu. |
| Tiền điều kiện | Người học đang làm đề thi HSK và ở phần thi Nghe hội thoại chọn 6 tranh chung. |
| Hậu điều kiện | Ghi nhận bức tranh đã chọn cho từng câu hỏi vào bài thi (kết quả hiển thị sau khi nộp bài). |

### **5.2. Luồng chính (Main Flow)**

| Bước | Tác nhân | Hành động |
| :---- | :---- | :---- |
| 1 | Hệ thống | Hiển thị: Ngân hàng 6 bức tranh chung cố định (A, B, C, D, E, F) ở phần tham chiếu và số thứ tự câu hỏi kèm nút phát âm thanh của câu đang làm trong cụm 5 câu. |
| 2 | Người học | Bấm nút phát âm thanh của câu hỏi hiện tại để nghe lại (hệ thống đã tự động phát 2 lần liên tiếp khi vào câu; người học được nghe lại tối đa 2 lần, tổng tối đa 4 lần/câu). |
| 3 | Hệ thống | Tự động phát đoạn ghi âm hội thoại tiếng Trung của câu hỏi đó 02 lần liên tiếp ngay khi vào câu. |
| 4 | Người học | Đối chiếu nội dung hội thoại với 6 tranh chung phía trên và bấm chọn 01 bức tranh (A–F) tương ứng cho câu hỏi đó. |
| 5 | Hệ thống | Ghi nhận lựa chọn cho câu hỏi hiện tại, làm sáng trạng thái chọn và chưa hiển thị kết quả đúng/sai. |
| 6 | \- | Tiếp tục làm các câu hỏi tiếp theo trong cụm 5 câu (hoặc hoàn thành cụm câu hỏi). |

### **5.3. Luồng rẽ nhánh & Ngoại lệ (Alternative & Exception Flows)**

| Mã luồng | Điều kiện kích hoạt | Hành động xử lý |
| :---- | :---- | :---- |
| AF-01 | Đổi đáp án tranh | Người học bấm chọn bức tranh khác trong 6 tranh cho câu hỏi trước khi nộp bài. Hệ thống tự động cập nhật lại lựa chọn mới. |
| AF-02 | Nghe lại hội thoại | Bấm nút phát âm thanh của câu hỏi để nghe lại đoạn hội thoại (hệ thống đã tự động phát 2 lần liên tiếp khi vào câu; cho phép nghe lại tối đa 2 lần, tổng tối đa 4 lần/câu). |
| AF-03 | Bấm nút Quay lại | Hiển thị thông báo: "Dừng bài thi?" ➔ Nếu xác nhận thoát thì dừng làm bài và trở về màn hình danh sách đề thi. |
| EF-01 | Lỗi âm thanh | Thông báo: "Không thể phát âm thanh" kèm nút "Thử lại". |
| EF-02 | Lỗi tải tranh | Thông báo: "Lỗi tải danh sách hình ảnh" kèm nút "Thử lại". |

### **5.4. Ràng buộc dữ liệu (Validation Rules \- VR)**

| Mã VR | Trường dữ liệu | Ràng buộc dữ liệu | Thông báo hiển thị |
| :---- | :---- | :---- | :---- |
| VR01 | Lựa chọn đáp án | Mỗi câu hỏi bắt buộc chọn duy nhất 1 trong 6 tranh chung (A, B, C, D, E hoặc F). | "Vui lòng chọn 1 bức tranh" |
| VR02 | Tệp âm thanh hội thoại | • File .mp3, .m4a, .aac, .wav (dung lượng ≤ 5MB, thời lượng 5–30s). • Gồm đoạn hội thoại 2 người rõ tiếng, chuẩn bản xứ; hệ thống tự động phát 2 lần liên tiếp khi vào câu, người học được bấm nghe lại tối đa 2 lần (tổng tối đa 4 lần/câu). | "Không thể phát âm thanh" |
| VR03 | Ngân hàng 6 hình ảnh chung | • Bắt buộc có đủ 06 tệp hình ảnh độc lập (A đến F) cố định cho toàn bộ cụm 5 câu hỏi. • File .jpg, .png, .webp (dung lượng ≤ 3MB/ảnh, rõ nét). | "Lỗi tải danh sách hình ảnh" |

### **5.5. Quy tắc nghiệp vụ (Business Rules \- BR)**

| Mã BR | Tên quy tắc | Nội dung quy định |
| :---- | :---- | :---- |
| BR01 | Số lần phát âm thanh | Tự động phát 2 lần liên tiếp khi vào câu; cho phép nghe lại tối đa 2 lần (tổng tối đa 4 lần/câu). |
| BR02 | Ngân hàng 6 tranh dùng chung | 6 bức tranh (A–F) được hiển thị cố định dùng chung cho cả cụm 5 câu hỏi; mỗi câu hỏi người học chọn 1 tranh phù hợp. |
| BR03 | Quy tắc chọn đơn | Mỗi câu hỏi chỉ được liên kết với duy nhất 1 bức tranh. |
| BR04 | Ẩn kết quả lúc làm bài | Hệ thống không báo đúng/sai ngay; chỉ chấm điểm và hiển thị đáp án chi tiết sau khi người học nộp toàn bộ bài thi. |
| BR05 | Tự động lưu tiến độ | Đáp án đã chọn của từng câu trong cụm được tự động lưu lại khi chuyển đổi giữa các câu. |

## **6\. Chức năng Luyện thi HSK \- Nghe hội thoại chọn đáp án A, B, C (HSK-LISTEN-MCQ: Listening Dialogue 3-Option MCQ)**

### **6.1. Thông tin chung**

| Mã chức năng | HSK-LISTEN-MCQ |
| :---- | :---- |
| Tên chức năng | Nghe hội thoại chọn đáp án A, B, C (Listening Dialogue 3-Option MCQ) |
| Tác nhân (Actor) | Người học (Learner) |
| Mục tiêu | Nghe đoạn hội thoại kèm câu hỏi và chọn 1 trong 3 đáp án chữ Hán (kèm Pinyin) A, B, C phù hợp. |
| Tiền điều kiện | Người học đang trong bài thi HSK và ở câu hỏi dạng Nghe hội thoại chọn đáp án văn bản. |
| Hậu điều kiện | Ghi nhận phương án A, B hoặc C đã chọn vào bài thi (kết quả hiển thị sau khi nộp bài). |

### **6.2. Luồng chính (Main Flow)**

| Bước | Tác nhân | Hành động |
| :---- | :---- | :---- |
| 1 | Hệ thống | Hiển thị: Nút phát âm thanh, số thứ tự câu hỏi và 3 phương án lựa chọn A, B, C (chữ Hán kèm Pinyin phía trên). |
| 2 | Người học | Bấm nút phát âm thanh để nghe lại đoạn hội thoại và câu hỏi (hệ thống đã tự động phát 2 lần liên tiếp khi vào câu; người học được nghe lại tối đa 2 lần, tổng tối đa 4 lần/câu). |
| 3 | Hệ thống | Tự động phát đoạn ghi âm hội thoại kèm câu hỏi 02 lần liên tiếp ngay khi vào câu. |
| 4 | Người học | Lắng nghe câu hỏi và bấm chọn 01 trong 3 đáp án (A, B hoặc C). |
| 5 | Hệ thống | Ghi nhận lựa chọn, làm sáng nút đáp án đã chọn và chưa hiển thị kết quả đúng/sai. |
| 6 | \- | Kết thúc chức năng (hoặc chuyển sang câu hỏi tiếp theo). |

### **6.3. Luồng rẽ nhánh & Ngoại lệ (Alternative & Exception Flows)**

| Mã luồng | Điều kiện kích hoạt | Hành động xử lý |
| :---- | :---- | :---- |
| AF-01 | Đổi đáp án chọn | Người học bấm chọn phương án khác (A, B hoặc C) trước khi nộp bài. Hệ thống tự động chuyển trạng thái chọn sang đáp án mới. |
| AF-02 | Nghe lại âm thanh | Bấm nút phát âm thanh để nghe lại đoạn hội thoại và câu hỏi (hệ thống đã tự động phát 2 lần liên tiếp khi vào câu; cho phép nghe lại tối đa 2 lần, tổng tối đa 4 lần/câu). |
| AF-03 | Bấm nút Quay lại | Hiển thị thông báo: "Dừng bài thi?" ➔ Nếu xác nhận thoát thì dừng làm bài và trở về màn hình danh sách đề thi. |
| EF-01 | Lỗi âm thanh | Thông báo: "Không thể phát âm thanh" kèm nút "Thử lại". |
| EF-02 | Lỗi tải nội dung đáp án | Thông báo: "Không thể tải nội dung câu hỏi" kèm nút "Thử lại". |

### **6.4. Ràng buộc dữ liệu (Validation Rules \- VR)**

| Mã VR | Trường dữ liệu | Ràng buộc dữ liệu | Thông báo hiển thị |
| :---- | :---- | :---- | :---- |
| VR01 | Lựa chọn đáp án | Bắt buộc chọn duy nhất 1 trong 3 phương án (A, B hoặc C). | "Vui lòng chọn đáp án" |
| VR02 | Tệp âm thanh | • File .mp3, .m4a, .aac, .wav (dung lượng ≤ 5MB, thời lượng 5–30s). • Gồm hội thoại 2 nhân vật \+ 1 câu hỏi; giọng chuẩn rõ tiếng; hệ thống tự động phát 2 lần liên tiếp khi vào câu, người học được bấm nghe lại tối đa 2 lần (tổng tối đa 4 lần/câu). | "Không thể phát âm thanh" |
| VR03 | Nội dung 3 đáp án (A, B, C) | • Bắt buộc có đủ 03 đáp án độc lập A, B, C (không để trống). • Mỗi đáp án gồm Chữ Hán (Hanzi) và Phiên âm (Pinyin) đặt phía trên; định dạng text rõ ràng. | "Lỗi nội dung đáp án câu hỏi" |

### **6.5. Quy tắc nghiệp vụ (Business Rules \- BR)**

| Mã BR | Tên quy tắc | Nội dung quy định |
| :---- | :---- | :---- |
| BR01 | Số lần phát âm thanh | Tự động phát 2 lần liên tiếp khi vào câu; cho phép nghe lại tối đa 2 lần (tổng tối đa 4 lần/câu). |
| BR02 | Quy tắc chọn đơn | Người học chỉ được chọn duy nhất 1 đáp án (A, B hoặc C) cho mỗi câu hỏi; khi chọn phương án mới sẽ tự động hủy phương án cũ. |
| BR03 | Hiển thị Pinyin | Toàn bộ chữ Hán trong 3 phương án A, B, C bắt buộc hiển thị kèm Pinyin ngay phía trên chữ tương ứng. |
| BR04 | Ẩn kết quả lúc làm bài | Hệ thống không báo đúng/sai ngay; chỉ chấm điểm và hiển thị đáp án chi tiết sau khi người học nộp bài thi. |
| BR05 | Tự động lưu tiến độ | Đáp án đã chọn được tự động lưu lại khi người học chuyển đổi giữa các câu hỏi trong đề thi. |

## **7\. Chức năng Luyện thi HSK \- Đọc hiểu phán đoán Đúng / Sai (HSK-READ-TF: Reading True/False Picture-Word Matching)**

### **7.1. Thông tin chung**

| Mã chức năng | HSK-READ-TF |
| :---- | :---- |
| Tên chức năng | Đọc hiểu \- Phán đoán tranh và từ vựng Đúng / Sai (Reading True/False Picture-Word Matching) |
| Tác nhân (Actor) | Người học (Learner) |
| Mục tiêu | Đọc từ vựng/cụm từ chữ Hán (kèm Pinyin) và quan sát hình ảnh để phán đoán nội dung Đúng hoặc Sai. |
| Tiền điều kiện | Người học đang trong bài thi HSK và ở phần thi Đọc hiểu (dạng câu hỏi Phán đoán Đúng/Sai). |
| Hậu điều kiện | Ghi nhận đáp án đã chọn vào bài thi (kết quả hiển thị sau khi nộp bài). |

### **7.2. Luồng chính (Main Flow)**

| Bước | Tác nhân | Hành động |
| :---- | :---- | :---- |
| 1 | Hệ thống | Hiển thị: 01 bức tranh minh họa, 01 từ vựng/cụm từ chữ Hán (kèm Pinyin) và 2 nút chọn: Đúng (✓) / Sai (✗). |
| 2 | Người học | Đọc nội dung từ vựng, quan sát hình ảnh và bấm chọn "Đúng" (khớp tranh) hoặc "Sai" (không khớp tranh). |
| 3 | Hệ thống | Ghi nhận lựa chọn, làm sáng nút đã chọn và chưa hiển thị kết quả đúng/sai. |
| 4 | \- | Kết thúc chức năng (hoặc chuyển sang câu hỏi tiếp theo). |

### **7.3. Luồng rẽ nhánh & Ngoại lệ (Alternative & Exception Flows)**

| Mã luồng | Điều kiện kích hoạt | Hành động xử lý |
| :---- | :---- | :---- |
| AF-01 | Đổi đáp án | Người học bấm chọn lại giữa "Đúng" và "Sai" trước khi nộp bài. Hệ thống tự động cập nhật lại lựa chọn mới. |
| AF-02 | Bấm nút Quay lại | Hiển thị thông báo: "Dừng bài thi?" ➔ Nếu xác nhận thoát thì dừng làm bài và trở về màn hình danh sách đề thi. |
| EF-01 | Lỗi hình ảnh | Thông báo: "Không thể tải hình ảnh minh họa" kèm nút "Thử lại". |

### **7.4. Ràng buộc dữ liệu (Validation Rules \- VR)**

| Mã VR | Trường dữ liệu | Ràng buộc dữ liệu | Thông báo hiển thị |
| :---- | :---- | :---- | :---- |
| VR01 | Lựa chọn đáp án | Bắt buộc chọn 1 trong 2 giá trị: Đúng hoặc Sai. | "Vui lòng chọn Đúng hoặc Sai" |
| VR02 | Tệp hình ảnh | • Bắt buộc có đúng 01 tệp hình ảnh minh họa. • File .jpg, .png, .webp (dung lượng ≤ 3MB, hình ảnh rõ nét). | "Không thể tải hình ảnh" |
| VR03 | Từ vựng / Cụm từ | Bắt buộc có dữ liệu text chữ Hán (Hanzi) kèm phiên âm (Pinyin); không được để trống. | "Lỗi dữ liệu câu hỏi" |

### **7.5. Quy tắc nghiệp vụ (Business Rules \- BR)**

| Mã BR | Tên quy tắc | Nội dung quy định |
| :---- | :---- | :---- |
| BR01 | Tiêu chuẩn Đúng / Sai | • Chọn Đúng: Nghĩa của từ vựng/cụm từ hoàn toàn trùng khớp với bức tranh. • Chọn Sai: Nghĩa của từ vựng/cụm từ không khớp hoặc trái ngược với bức tranh. |
| BR02 | Hiển thị Pinyin | Từ vựng/cụm từ chữ Hán hiển thị kèm phiên âm Pinyin. |
| BR03 | Ẩn kết quả lúc làm bài | Hệ thống không báo đúng/sai ngay; chỉ chấm điểm và hiển thị đáp án chi tiết sau khi người học nộp bài thi. |
| BR04 | Tự động lưu tiến độ | Đáp án đã chọn được tự động lưu lại khi người học chuyển đổi giữa các câu hỏi trong đề thi. |

## **8\. Chức năng Luyện thi HSK \- Đọc hiểu ghép câu với tranh (HSK-READ-6PIC: Reading Sentence 6-Picture Matching)**

### **8.1. Thông tin chung**

| Mã chức năng | HSK-READ-6PIC |
| :---- | :---- |
| Tên chức năng | Đọc hiểu \- Đọc câu ghép tranh phù hợp (Reading Sentence 6-Picture Matching) |
| Tác nhân (Actor) | Người học (Learner) |
| Mục tiêu | Đọc câu trần thuật tiếng Trung (kèm Pinyin) và chọn 1 trong 6 bức tranh (A–F) mô tả đúng nội dung câu. |
| Tiền điều kiện | Người học đang trong bài thi HSK và ở phần thi Đọc hiểu (dạng câu hỏi Ghép câu với 6 tranh). |
| Hậu điều kiện | Ghi nhận bức tranh đã chọn vào bài thi (kết quả hiển thị sau khi nộp bài). |

### **8.2. Luồng chính (Main Flow)**

| Bước | Tác nhân | Hành động |
| :---- | :---- | :---- |
| 1 | Hệ thống | Hiển thị: 01 câu trần thuật tiếng Trung (kèm Pinyin) và lưới 6 bức tranh lựa chọn (Tranh A, B, C, D, E, F). |
| 2 | Người học | Đọc và hiểu nghĩa câu tiếng Trung, quan sát 6 bức tranh và bấm chọn 01 bức tranh phù hợp nhất. |
| 3 | Hệ thống | Ghi nhận lựa chọn, làm sáng viền bức tranh đã chọn và chưa hiển thị kết quả đúng/sai. |
| 4 | \- | Kết thúc chức năng (hoặc chuyển sang câu hỏi tiếp theo). |

### **8.3. Luồng rẽ nhánh & Ngoại lệ (Alternative & Exception Flows)**

| Mã luồng | Điều kiện kích hoạt | Hành động xử lý |
| :---- | :---- | :---- |
| AF-01 | Đổi lựa chọn tranh | Người học bấm chọn bức tranh khác trong 6 tranh trước khi nộp bài. Hệ thống tự động chuyển vùng chọn sang tranh mới. |
| AF-02 | Bấm nút Quay lại | Hiển thị thông báo: "Dừng bài thi?" ➔ Nếu xác nhận thoát thì dừng làm bài và trở về màn hình danh sách đề thi. |
| EF-01 | Lỗi hình ảnh | Thông báo: "Lỗi tải danh sách hình ảnh" kèm nút "Thử lại". |

### **8.4. Ràng buộc dữ liệu (Validation Rules \- VR)**

| Mã VR | Trường dữ liệu | Ràng buộc dữ liệu | Thông báo hiển thị |
| :---- | :---- | :---- | :---- |
| VR01 | Lựa chọn đáp án | Bắt buộc chọn duy nhất 1 trong 6 tranh (A, B, C, D, E hoặc F). | "Vui lòng chọn 1 bức tranh" |
| VR02 | Tệp 6 hình ảnh | • Bắt buộc có đủ 06 tệp hình ảnh độc lập (A đến F). • File .jpg, .png, .webp (dung lượng ≤ 3MB/ảnh, hình ảnh rõ nét). | "Lỗi tải danh sách hình ảnh" |
| VR03 | Câu trần thuật | Bắt buộc có dữ liệu text câu chữ Hán (Hanzi) kèm phiên âm (Pinyin); không được để trống. | "Lỗi dữ liệu câu hỏi" |

### **8.5. Quy tắc nghiệp vụ (Business Rules \- BR)**

| Mã BR | Tên quy tắc | Nội dung quy định |
| :---- | :---- | :---- |
| BR01 | Quy tắc chọn đơn | Người học chỉ được chọn duy nhất 1 trong 6 tranh cho mỗi câu hỏi; khi bấm tranh mới sẽ tự động hủy chọn tranh cũ. |
| BR02 | Hiển thị Pinyin | Câu trần thuật chữ Hán bắt buộc hiển thị kèm phiên âm Pinyin. |
| BR03 | Ẩn kết quả lúc làm bài | Hệ thống không báo đúng/sai ngay; chỉ chấm điểm và hiển thị đáp án chi tiết sau khi người học nộp toàn bộ bài thi. |
| BR04 | Tự động lưu tiến độ | Bức tranh đã chọn được tự động lưu lại khi người học chuyển đổi giữa các câu hỏi trong đề thi. |

## **9\. Chức năng Luyện thi HSK \- Đọc hiểu chọn câu trả lời tương ứng (HSK-READ-MATCH-QA: Reading Question-Answer Matching)**

### **9.1. Thông tin chung**

| Mã chức năng | HSK-READ-MATCH-QA |
| :---- | :---- |
| Tên chức năng | Đọc hiểu \- Chọn câu trả lời tương ứng (Reading Question-Answer Matching) |
| Tác nhân (Actor) | Người học (Learner) |
| Mục tiêu | Đọc câu hỏi và chọn câu trả lời tương ứng phù hợp nhất trong danh sách các câu trả lời (A–E). |
| Tiền điều kiện | Người học đang trong bài thi HSK và ở phần thi Đọc hiểu (dạng câu hỏi Chọn câu trả lời tương ứng). |
| Hậu điều kiện | Ghi nhận câu trả lời đã chọn vào bài thi (kết quả hiển thị sau khi nộp bài). |

### **9.2. Luồng chính (Main Flow)**

| Bước | Tác nhân | Hành động |
| :---- | :---- | :---- |
| 1 | Hệ thống | Hiển thị: Danh sách câu hỏi (đánh số thứ tự) và danh sách các câu trả lời lựa chọn (ký hiệu A–E), toàn bộ chữ Hán hiển thị kèm Pinyin. |
| 2 | Người học | Đọc hiểu nội dung từng câu hỏi và bấm chọn câu trả lời tương ứng (A, B, C, D hoặc E) cho mỗi câu hỏi. |
| 3 | Hệ thống | Ghi nhận câu trả lời đã chọn, làm sáng nút/ô đáp án đã chọn và chưa hiển thị kết quả đúng/sai. |
| 4 | \- | Kết thúc chức năng (hoặc chuyển sang câu hỏi/phần thi tiếp theo). |

### **9.3. Luồng rẽ nhánh & Ngoại lệ (Alternative & Exception Flows)**

| Mã luồng | Điều kiện kích hoạt | Hành động xử lý |
| :---- | :---- | :---- |
| AF-01 | Đổi đáp án đã chọn | Người học bấm chọn câu trả lời khác cho câu hỏi trước khi nộp bài. Hệ thống tự động chuyển trạng thái chọn sang đáp án mới. |
| AF-02 | Bấm nút Quay lại | Hiển thị thông báo: "Dừng bài thi?" ➔ Nếu xác nhận thoát thì dừng làm bài và trở về màn hình danh sách đề thi. |
| EF-01 | Lỗi tải dữ liệu | Thông báo: "Không thể tải nội dung câu hỏi" kèm nút "Thử lại". |

### **9.4. Ràng buộc dữ liệu (Validation Rules \- VR)**

| Mã VR | Trường dữ liệu | Ràng buộc dữ liệu | Thông báo hiển thị |
| :---- | :---- | :---- | :---- |
| VR01 | Lựa chọn câu trả lời | Mỗi câu hỏi bắt buộc chọn 01 câu trả lời trong danh sách (A, B, C, D hoặc E). | "Vui lòng chọn câu trả lời" |
| VR02 | Danh sách Hỏi \- Đáp | • Bắt buộc có đủ 5 câu hỏi và 5 câu trả lời tương ứng. • Toàn bộ dữ liệu chữ Hán (Hanzi) kèm phiên âm (Pinyin); không được để trống. | "Lỗi dữ liệu câu hỏi" |

### **9.5. Quy tắc nghiệp vụ (Business Rules \- BR)**

| Mã BR | Tên quy tắc | Nội dung quy định |
| :---- | :---- | :---- |
| BR01 | Quy tắc chọn đơn | Mỗi câu hỏi chỉ được chọn duy nhất 01 câu trả lời; khi chọn đáp án mới cho câu hỏi, đáp án cũ sẽ tự động bị thay thế. |
| BR02 | Hiển thị Pinyin | Toàn bộ câu hỏi và câu trả lời chữ Hán bắt buộc hiển thị kèm phiên âm Pinyin. |
| BR03 | Ẩn kết quả lúc làm bài | Hệ thống không báo đúng/sai ngay; chỉ chấm điểm và hiển thị đáp án chi tiết sau khi người học nộp toàn bộ bài thi. |
| BR04 | Tự động lưu tiến độ | Đáp án đã chọn của các câu được tự động lưu lại khi người học chuyển đổi giữa các câu trong đề thi. |

## **10\. Chức năng Luyện thi HSK \- Đọc hiểu điền từ vào chỗ trống (HSK-READ-FILL-BLANK: Reading Fill-in-the-Blank Cloze Test)**

### **10.1. Thông tin chung**

| Mã chức năng | HSK-READ-FILL-BLANK |
| :---- | :---- |
| Tên chức năng | Đọc hiểu \- Điền từ vào chỗ trống (Reading Fill-in-the-Blank Cloze Test) |
| Tác nhân (Actor) | Người học (Learner) |
| Mục tiêu | Đọc câu văn có vị trí khuyết (chỗ trống ( )) và chọn 1 từ thích hợp trong danh sách 5 từ cho trước (A–E) để hoàn chỉnh câu. |
| Tiền điều kiện | Người học đang trong bài thi HSK và ở phần thi Đọc hiểu (dạng câu hỏi Điền từ vào chỗ trống). |
| Hậu điều kiện | Ghi nhận từ đã chọn điền vào câu trong bài thi (kết quả hiển thị sau khi nộp bài). |

### **10.2. Luồng chính (Main Flow)**

| Bước | Tác nhân | Hành động |
| :---- | :---- | :---- |
| 1 | Hệ thống | Hiển thị: Ngân hàng 5 từ vựng cho trước (ký hiệu A, B, C, D, E) và danh sách 5 câu văn có chỗ trống ( ), toàn bộ chữ Hán hiển thị kèm Pinyin. |
| 2 | Người học | Đọc hiểu ngữ cảnh từng câu văn và bấm chọn 01 từ thích hợp (A, B, C, D hoặc E) để điền vào chỗ trống của câu. |
| 3 | Hệ thống | Ghi nhận từ đã chọn, hiển thị từ vào vị trí chỗ trống (hoặc làm sáng trạng thái chọn) và chưa hiển thị kết quả đúng/sai. |
| 4 | \- | Kết thúc chức năng (hoặc chuyển sang câu hỏi/phần thi tiếp theo). |

### **10.3. Luồng rẽ nhánh & Ngoại lệ (Alternative & Exception Flows)**

| Mã luồng | Điều kiện kích hoạt | Hành động xử lý |
| :---- | :---- | :---- |
| AF-01 | Đổi từ đã chọn | Người học bấm chọn từ khác trong danh sách trước khi nộp bài. Hệ thống tự động thay thế từ cũ bằng từ mới tại chỗ trống. |
| AF-02 | Bấm nút Quay lại | Hiển thị thông báo: "Dừng bài thi?" ➔ Nếu xác nhận thoát thì dừng làm bài và trở về màn hình danh sách đề thi. |
| EF-01 | Lỗi tải dữ liệu | Thông báo: "Không thể tải nội dung câu hỏi" kèm nút "Thử lại". |

### **10.4. Ràng buộc dữ liệu (Validation Rules \- VR)**

| Mã VR | Trường dữ liệu | Ràng buộc dữ liệu | Thông báo hiển thị |
| :---- | :---- | :---- | :---- |
| VR01 | Lựa chọn từ điền | Mỗi câu văn có chỗ trống bắt buộc chọn 01 từ trong danh sách 5 từ cho trước (A, B, C, D hoặc E). | "Vui lòng chọn từ điền vào chỗ trống" |
| VR02 | Dữ liệu từ & Câu văn | • Bắt buộc có đủ 5 từ cho trước và 5 câu văn có chỗ trống tương ứng. • Toàn bộ dữ liệu chữ Hán (Hanzi) kèm phiên âm (Pinyin); không được để trống. | "Lỗi dữ liệu câu hỏi" |

### **10.5. Quy tắc nghiệp vụ (Business Rules \- BR)**

| Mã BR | Tên quy tắc | Nội dung quy định |
| :---- | :---- | :---- |
| BR01 | Quy tắc chọn đơn | Mỗi chỗ trống chỉ được chọn duy nhất 01 từ thích hợp; khi chọn từ mới cho câu, từ cũ sẽ tự động bị thay thế. |
| BR02 | Hiển thị Pinyin | Toàn bộ từ cho trước và câu văn chữ Hán bắt buộc hiển thị kèm phiên âm Pinyin. |
| BR03 | Ẩn kết quả lúc làm bài | Hệ thống không báo đúng/sai ngay; chỉ chấm điểm và hiển thị đáp án chi tiết sau khi người học nộp toàn bộ bài thi. |
| BR04 | Tự động lưu tiến độ | Từ đã chọn điền vào các câu được tự động lưu lại khi người học chuyển đổi giữa các câu trong đề thi. |

## **11\. Chức năng Luyện thi HSK \- Đọc hiểu phán đoán 2 câu văn Đúng / Sai (HSK-READ-PASSAGE-TF: Reading Passage-Statement True/False)**

### **11.1. Thông tin chung**

| Mã chức năng | HSK-READ-PASSAGE-TF |
| :---- | :---- |
| Tên chức năng | Đọc hiểu \- Phán đoán câu nhận định Đúng / Sai (Reading Passage-Statement True/False) |
| Tác nhân (Actor) | Người học (Learner) |
| Mục tiêu | Đọc đoạn văn ngắn tiếng Trung và đối chiếu với câu nhận định (★) để phán đoán ý nghĩa Đúng (trùng khớp) hoặc Sai (không trùng khớp). |
| Tiền điều kiện | Người học đang trong bài thi HSK 2 và ở phần thi Đọc hiểu (dạng câu hỏi Phán đoán 2 câu văn). |
| Hậu điều kiện | Ghi nhận đáp án đã chọn vào bài thi (kết quả hiển thị sau khi nộp bài). |

### **11.2. Luồng chính (Main Flow)**

| Bước | Tác nhân | Hành động |
| :---- | :---- | :---- |
| 1 | Hệ thống | Hiển thị: 01 đoạn văn ngắn gốc, số thứ tự câu hỏi kèm 01 câu nhận định cần đối chiếu (toàn bộ chữ Hán kèm Pinyin) và 2 nút chọn: Đúng (✓) / Sai (✗). |
| 2 | Người học | Đọc hiểu đoạn văn gốc, đối chiếu với câu nhận định và bấm chọn "Đúng" (khớp nghĩa) hoặc "Sai" (không khớp/mâu thuẫn). |
| 3 | Hệ thống | Ghi nhận lựa chọn, làm sáng nút đã chọn và chưa hiển thị kết quả đúng/sai. |
| 4 | \- | Kết thúc chức năng (hoặc chuyển sang câu hỏi tiếp theo). |

### **11.3. Luồng rẽ nhánh & Ngoại lệ (Alternative & Exception Flows)**

| Mã luồng | Điều kiện kích hoạt | Hành động xử lý |
| :---- | :---- | :---- |
| AF-01 | Đổi đáp án chọn | Người học bấm chọn lại giữa "Đúng" và "Sai" trước khi nộp bài. Hệ thống tự động chuyển sang lựa chọn mới. |
| AF-02 | Bấm nút Quay lại | Hiển thị thông báo: "Dừng bài thi?" ➔ Nếu xác nhận thoát thì dừng làm bài và trở về màn hình danh sách đề thi. |
| EF-01 | Lỗi tải dữ liệu | Thông báo: "Không thể tải nội dung câu hỏi" kèm nút "Thử lại". |

### **11.4. Ràng buộc dữ liệu (Validation Rules \- VR)**

| Mã VR | Trường dữ liệu | Ràng buộc dữ liệu | Thông báo hiển thị |
| :---- | :---- | :---- | :---- |
| VR01 | Lựa chọn đáp án | Bắt buộc chọn 1 trong 2 nút lựa chọn: Đúng hoặc Sai. | "Vui lòng chọn Đúng hoặc Sai" |
| VR02 | Dữ liệu đoạn văn & Câu nhận định | • Bắt buộc có đủ 01 đoạn văn gốc và 01 câu nhận định (★) tương ứng. • Toàn bộ dữ liệu text chữ Hán (Hanzi) kèm phiên âm (Pinyin); không được để trống. | "Lỗi dữ liệu câu hỏi" |

### **11.5. Quy tắc nghiệp vụ (Business Rules \- BR)**

| Mã BR | Tên quy tắc | Nội dung quy định |
| :---- | :---- | :---- |
| BR01 | Tiêu chuẩn Đúng / Sai | • Chọn Đúng: Ý nghĩa của câu nhận định hoàn toàn trùng khớp hoặc được suy luận đúng từ đoạn văn gốc. • Chọn Sai: Ý nghĩa của câu nhận định mâu thuẫn, trái ngược hoặc không chính xác so với đoạn văn gốc. |
| BR02 | Hiển thị Pinyin | Toàn bộ chữ Hán trong đoạn văn gốc và câu nhận định bắt buộc hiển thị kèm phiên âm Pinyin. |
| BR03 | Ẩn kết quả lúc làm bài | Hệ thống không báo đúng/sai ngay; chỉ chấm điểm và hiển thị đáp án chi tiết sau khi người học nộp toàn bộ bài thi. |
| BR04 | Tự động lưu tiến độ | Đáp án đã chọn được tự động lưu lại khi người học chuyển đổi giữa các câu hỏi trong đề thi. |

## **12\. Chức năng Luyện thi HSK 3 \- Nghe hội thoại phán đoán Đúng / Sai (HSK3-LISTEN-DIALOG-TF: HSK 3 Listening Dialogue Statement True/False Quiz)**

### **12.1. Thông tin chung**

| Mã chức năng | HSK3-LISTEN-DIALOG-TF |
| :---- | :---- |
| Tên chức năng | Luyện thi HSK 3 \- Nghe hội thoại phán đoán Đúng / Sai (HSK 3 Listening Dialogue Statement True/False Quiz) |
| Tác nhân (Actor) | Người học (Learner) |
| Mục tiêu | Nghe đoạn hội thoại tiếng Trung, đọc câu trần thuật chữ Hán trên màn hình để phán đoán nội dung Đúng hoặc Sai. |
| Tiền điều kiện | Người học đang trong bài thi HSK 3 và ở phần thi Nghe hiểu (dạng câu hỏi Nghe hội thoại phán đoán Đúng/Sai). |
| Hậu điều kiện | Ghi nhận đáp án đã chọn vào bài thi (kết quả hiển thị sau khi nộp bài). |

### **12.2. Luồng chính (Main Flow)**

| Bước | Tác nhân | Hành động |
| :---- | :---- | :---- |
| 1 | Hệ thống | Hiển thị: Nút phát âm thanh, số thứ tự câu hỏi, 01 câu trần thuật chữ Hán (không có Pinyin) và 2 nút chọn: Đúng (✓) / Sai (✗). |
| 2 | Người học | Bấm nút phát âm thanh để nghe lại đoạn hội thoại (hệ thống đã tự động phát 2 lần liên tiếp khi vào câu; người học được nghe lại tối đa 2 lần, tổng tối đa 4 lần/câu). |
| 3 | Hệ thống | Tự động phát đoạn ghi âm hội thoại ngắn tiếng Trung 02 lần liên tiếp ngay khi vào câu. |
| 4 | Người học | Đọc hiểu câu trần thuật chữ Hán, đối chiếu nội dung hội thoại vừa nghe và bấm chọn "Đúng" (khớp nghĩa) hoặc "Sai" (không khớp/mâu thuẫn). |
| 5 | Hệ thống | Ghi nhận lựa chọn, làm sáng nút đã chọn và chưa hiển thị kết quả đúng/sai. |
| 6 | \- | Kết thúc câu hỏi và chuyển sang câu tiếp theo. |

### **12.3. Luồng rẽ nhánh & Ngoại lệ (Alternative & Exception Flows)**

| Mã luồng | Điều kiện kích hoạt | Hành động xử lý |
| :---- | :---- | :---- |
| AF-01 | Đổi đáp án chọn | Người học bấm chọn lại giữa "Đúng" và "Sai" trước khi nộp bài. Hệ thống tự động chuyển sang lựa chọn mới. |
| AF-02 | Nghe lại âm thanh | Bấm nút phát âm thanh để nghe lại đoạn hội thoại (hệ thống đã tự động phát 2 lần liên tiếp khi vào câu; cho phép nghe lại tối đa 2 lần, tổng tối đa 4 lần/câu). |
| AF-03 | Bấm nút Quay lại | Hiển thị thông báo: "Dừng bài thi?" ➔ Nếu xác nhận thoát thì dừng làm bài và trở về màn hình danh sách đề thi. |
| EF-01 | Lỗi âm thanh | Thông báo: "Không thể phát âm thanh" kèm nút "Thử lại". |
| EF-02 | Lỗi tải câu hỏi | Thông báo: "Không thể tải nội dung câu hỏi" kèm nút "Thử lại". |

### **12.4. Ràng buộc dữ liệu (Validation Rules \- VR)**

| Mã VR | Trường dữ liệu | Ràng buộc dữ liệu | Thông báo hiển thị |
| :---- | :---- | :---- | :---- |
| VR01 | Lựa chọn đáp án | Bắt buộc chọn 1 trong 2 nút lựa chọn: Đúng hoặc Sai. | "Vui lòng chọn Đúng hoặc Sai" |
| VR02 | Tệp âm thanh hội thoại | • File .mp3, .m4a, .aac, .wav (dung lượng ≤ 5MB, thời lượng 5–30s). • Đoạn hội thoại 2 người chuẩn giọng bản xứ; hệ thống tự động phát 2 lần liên tiếp khi vào câu, người học được bấm nghe lại tối đa 2 lần (tổng tối đa 4 lần/câu). | "Không thể phát âm thanh" |
| VR03 | Câu trần thuật chữ Hán | Bắt buộc có dữ liệu text chữ Hán (Hanzi) chuẩn cấp độ HSK 3 (không kèm Pinyin); không được để trống. | "Lỗi dữ liệu câu hỏi" |

### **12.5. Quy tắc nghiệp vụ (Business Rules \- BR)**

| Mã BR | Tên quy tắc | Nội dung quy định |
| :---- | :---- | :---- |
| BR01 | Số lần phát âm thanh | Tự động phát 2 lần liên tiếp khi vào câu; cho phép nghe lại tối đa 2 lần (tổng tối đa 4 lần/câu). |
| BR02 | Tiêu chuẩn Đúng / Sai | • Chọn Đúng: Ý nghĩa của câu trần thuật hoàn toàn trùng khớp với nội dung hội thoại. • Chọn Sai: Ý nghĩa của câu trần thuật mâu thuẫn hoặc không đúng với nội dung hội thoại. |
| BR03 | Quy chuẩn chữ Hán HSK 3 | Từ cấp độ HSK 3 trở lên, toàn bộ câu trần thuật và đề bài hiển thị 100% chữ Hán thuần túy, hoàn toàn không kèm phiên âm Pinyin. |
| BR04 | Ẩn kết quả lúc làm bài | Hệ thống không báo đúng/sai ngay; chỉ chấm điểm và hiển thị đáp án chi tiết sau khi người học nộp toàn bộ bài thi. |
| BR05 | Tự động lưu tiến độ | Đáp án đã chọn được tự động lưu lại khi người học chuyển đổi giữa các câu hỏi trong đề thi. |

## **13\. Chức năng Luyện thi HSK 3 \- Đọc hiểu đoạn văn chọn 1 trong 3 đáp án (HSK3-READ-PASSAGE-MCQ: HSK 3 Reading Short Passage 3-Option MCQ)**

### **13.1. Thông tin chung**

| Mã chức năng | HSK3-READ-PASSAGE-MCQ |
| :---- | :---- |
| **Tên chức năng** | Đọc hiểu đoạn văn chọn 1 trong 3 đáp án (HSK 3 Reading Short Passage 3-Option MCQ) |
| **Tác nhân (Actor)** | Người học (Learner) |
| **Mục tiêu** | Đọc đoạn văn ngắn (2–3 câu chữ Hán) và chọn 1 trong 3 đáp án (A, B, C) trả lời đúng cho câu hỏi |
| **Tiền điều kiện (Pre-condition)** | Người học đang làm đề thi HSK 3 tại phần Đọc hiểu (Phần 3: Câu 61–70) |
| **Hậu điều kiện (Post-condition)** | Ghi nhận đáp án đã chọn vào bài thi (kết quả chấm điểm hiển thị sau khi nộp bài) |

### **13.2. Luồng chính (Main Flow)**

| Bước | Tác nhân | Hành động |
| :---- | :---- | :---- |
| **1** | Hệ thống | Hiển thị: Danh sách 10 card câu hỏi gồm đoạn văn ngắn (2–3 câu), câu hỏi bên dưới và 3 nút đáp án (A, B, C). |
| **2** | Người học | Đọc đoạn văn ngắn, câu hỏi và đối chiếu 3 phương án A, B, C. |
| **3** | Người học | Bấm chọn 1 đáp án phù hợp nhất. |
| **4** | Hệ thống | Ghi nhận lựa chọn, làm sáng viền/nền đáp án đã chọn và cập nhật số câu đã làm (x/10). |
| **5** | \- | Kết thúc chức năng (chuyển câu tiếp theo hoặc nộp bài). |

### **13.3. Luồng rẽ nhánh & Ngoại lệ (Alternative & Exception Flows)**

| Mã luồng | Điều kiện | Hành động xử lý |
| :---- | :---- | :---- |
| **AF-01** | Đổi đáp án | Người học bấm chọn đáp án khác (A, B hoặc C) trong cùng câu hỏi trước khi nộp bài. |
| **AF-02** | Xem danh sách câu hỏi | Bấm nút "Danh sách" ở Footer để mở Drawer và chọn nhanh câu muốn làm. |
| **AF-03** | Bấm nút Thoát | Hiển thị hộp thoại: "Dừng bài thi?" ➔ Nếu xác nhận thoát thì dừng làm bài, không lưu kết quả dở dang và trở về màn hình danh sách đề thi. |
| **EF-01** | Hết giờ làm bài | Hệ thống tự động thu bài và chấm điểm các câu đã chọn. |
| **EF-02** | Lỗi tải dữ liệu đề | Hiển thị thông báo: "Không thể tải nội dung câu hỏi. Vui lòng thử lại" kèm nút "Thử lại". |

### **13.4. Ràng buộc dữ liệu (Validation Rules \- VR)**

| Mã VR | Trường dữ liệu | Ràng buộc | Thông báo lỗi |
| :---- | :---- | :---- | :---- |
| **VR01** | Đáp án được chọn | Bắt buộc chọn duy nhất 1 trong 3 đáp án (A, B hoặc C). | "Vui lòng chọn 1 đáp án" |
| **VR02** | Văn bản câu hỏi | Đoạn văn ngắn (2–3 câu) và câu hỏi 100% chữ Hán giản thể, không kèm Pinyin. | "Lỗi định dạng đề thi" |

### **13.5. Quy tắc nghiệp vụ (Business Rules \- BR)**

| Mã BR | Tên quy tắc | Nội dung |
| :---- | :---- | :---- |
| **BR01** | Quy tắc chọn đơn | Người học chỉ được chọn duy nhất 1 trong 3 đáp án; chọn đáp án mới thì đáp án cũ tự động bỏ chọn. |
| **BR02** | Logic từ đồng nghĩa | Đoạn văn kiểm tra đọc hiểu và tư duy logic; đáp án đúng thường sử dụng từ hoặc cụm từ đồng nghĩa với bài đọc. |
| **BR03** | Quy tắc không kèm Pinyin | Toàn bộ đoạn văn, câu hỏi và đáp án ở cấp độ HSK 3 không hiển thị Pinyin để chuẩn hóa theo kỳ thi thật. |

## **14\. Chức năng Luyện thi HSK 3 \- Sắp xếp từ thành câu hoàn chỉnh (HSK3-WRITE-WORD-ORDER: HSK 3 Writing Sentence Word Reordering)**

### **14.1. Thông tin chung**

| Mã chức năng | HSK3-WRITE-WORD-ORDER |
| :---- | :---- |
| **Tên chức năng** | Sắp xếp từ thành câu hoàn chỉnh (HSK 3 Writing Sentence Word Reordering) |
| **Tác nhân (Actor)** | Người học (Learner) |
| **Mục tiêu** | Sắp xếp các từ cho sẵn theo đúng trật tự để tạo thành câu hoàn chỉnh đúng ngữ pháp và dấu câu |
| **Tiền điều kiện** | Người học đang làm bài thi HSK 3 tại phần Viết (Phần 1: Câu 71–75) |
| **Hậu điều kiện** | Ghi nhận câu trả lời vào bài làm để chấm điểm |

### **14.2. Luồng chính (Main Flow)**

| Bước | Tác nhân | Hành động |
| :---- | :---- | :---- |
| **1** | Hệ thống | Hiển thị 5 câu hỏi; mỗi câu gồm các từ chưa được sắp xếp kèm dấu câu. |
| **2** | Người học | Sắp xếp các từ thành câu theo đúng thứ tự. |
| **3** | Hệ thống | Ghi nhận câu trả lời đã sắp xếp cho câu hỏi. |
| **4** | Người học | Chuyển sang câu hỏi tiếp theo hoặc nộp bài. |

### **14.3. Luồng rẽ nhánh & Ngoại lệ (Alternative & Exception Flows)**

| Mã luồng | Điều kiện | Hành động xử lý |
| :---- | :---- | :---- |
| **AF-01** | Thay đổi thứ tự từ | Người học điều chỉnh lại vị trí các từ đã sắp xếp trước khi nộp bài. |
| **AF-02** | Đặt lại câu | Người học chọn làm lại để xóa câu trả lời hiện tại và sắp xếp lại từ đầu. |
| **AF-03** | Chuyển câu hỏi | Người học chủ động chọn câu hỏi bất kỳ trong 5 câu phần Viết để làm. |
| **EF-01** | Hết giờ làm bài | Hệ thống tự động thu bài và chấm điểm câu trả lời hiện tại. |

### **14.4. Ràng buộc dữ liệu (Validation Rules \- VR)**

| Mã VR | Trường dữ liệu | Ràng buộc | Thông báo lỗi |
| :---- | :---- | :---- | :---- |
| **VR01** | Ký tự câu hỏi | 100% chữ Hán giản thể chuẩn HSK 3, hoàn toàn không có phiên âm Pinyin. | "Lỗi định dạng đề thi" |
| **VR02** | Độ dài tối đa | Giới hạn tối đa 50 ký tự (bao gồm chữ Hán và dấu câu); hệ thống chặn không cho nhập tiếp khi đạt giới hạn. | "Khóa không cho viết nữa" |

### **14.5. Quy tắc nghiệp vụ (Business Rules \- BR)**

| Mã BR | Tên quy tắc | Nội dung quy tắc |
| :---- | :---- | :---- |
| **BR01** | Tính điểm tuyệt đối | Câu trả lời chỉ được tính điểm khi sắp xếp đúng 100% theo đáp án chuẩn duy nhất của đề thi (không có đáp án tương đương, không chấm điểm từng phần). |
| **BR02** | Tự động lưu bài | Tự động lưu câu trả lời ngay khi người học thực hiện sắp xếp. |
| **BR03** | Không kèm Pinyin | Toàn bộ câu hỏi và từ vựng HSK 3 không hiển thị Pinyin để đánh giá chính xác năng lực nhận diện chữ Hán. |
| **BR04** | Đếm ký tự & Chặn nhập | Hệ thống hiển thị bộ đếm ký tự thời gian thực (x/50) ở góc ô nhập; tự động khóa/chặn không cho nhập thêm khi đã đạt đủ 50 ký tự. |

## **15\. Chức năng Luyện thi HSK 3 \- Điền chữ Hán theo phiên âm và ngữ cảnh (HSK3-WRITE-FILL-HANZI: HSK 3 Writing Fill-in-the-Blank Hanzi)**

### **15.1. Thông tin chung**

| Mã chức năng | HSK3-WRITE-FILL-HANZI |
| :---- | :---- |
| **Tên chức năng** | Điền chữ Hán theo phiên âm và ngữ cảnh (HSK 3 Writing Fill-in-the-Blank Hanzi) |
| **Tác nhân (Actor)** | Người học (Learner) |
| **Mục tiêu** | Nhìn phiên âm Pinyin gợi ý và dựa vào ngữ cảnh câu để điền đúng chữ Hán vào chỗ trống |
| **Tiền điều kiện** | Người học đang làm bài thi HSK 3 tại phần Viết (Phần 2: Câu 76–80) |
| **Hậu điều kiện** | Ghi nhận chữ Hán đã điền vào bài làm để chấm điểm |

### **15.2. Luồng chính (Main Flow)**

| Bước | Tác nhân | Hành động |
| :---- | :---- | :---- |
| **1** | Hệ thống | Hiển thị 5 câu hỏi; mỗi câu gồm một câu văn chữ Hán có 1 chỗ trống kèm phiên âm Pinyin gợi ý phía trên. |
| **2** | Người học | Đọc câu văn, quan sát phiên âm Pinyin và ngữ cảnh để xác định chữ Hán cần điền. |
| **3** | Người học | Nhập chữ Hán tương ứng vào chỗ trống. |
| **4** | Hệ thống | Ghi nhận chữ Hán đã nhập cho câu hỏi. |
| **5** | Người học | Chuyển sang câu hỏi tiếp theo hoặc nộp bài. |

### **15.3. Luồng rẽ nhánh & Ngoại lệ (Alternative & Exception Flows)**

| Mã luồng | Điều kiện | Hành động xử lý |
| :---- | :---- | :---- |
| **AF-01** | Chỉnh sửa câu trả lời | Người học xóa hoặc nhập lại chữ Hán khác vào chỗ trống trước khi nộp bài. |
| **AF-02** | Chuyển câu hỏi | Người học chủ động chọn câu hỏi bất kỳ trong 5 câu phần Viết để làm. |
| **EF-01** | Hết giờ làm bài | Hệ thống tự động thu bài và chấm điểm các chữ Hán hiện có trong bài làm. |

### **15.4. Ràng buộc dữ liệu (Validation Rules \- VR)**

| Mã VR | Trường dữ liệu | Ràng buộc | Thông báo lỗi |
| :---- | :---- | :---- | :---- |
| **VR01** | Định dạng ký tự | Bắt buộc là ký tự chữ Hán (Hanzi), không chấp nhận ký tự La-tinh hay số. | "Vui lòng nhập chữ Hán" |
| **VR02** | Độ dài tối đa | Giới hạn tối đa 50 ký tự (bao gồm chữ Hán và dấu câu); hệ thống chặn không cho nhập tiếp khi đạt giới hạn. | "Khóa không cho viết nữa" |

### **15.5. Quy tắc nghiệp vụ (Business Rules \- BR)**

| Mã BR | Tên quy tắc | Nội dung quy tắc |
| :---- | :---- | :---- |
| **BR01** | Tính điểm chính xác | Điểm chỉ được tính khi chữ Hán nhập vào khớp chính xác 100% với đáp án chuẩn của câu hỏi (đúng mặt chữ và phù hợp ngữ cảnh). |
| **BR02** | Tự động lưu bài | Tự động lưu chữ Hán đã nhập ngay khi người học thực hiện thao tác nhập. |
| **BR03** | Quy cách đề thi | Mỗi câu chỉ khuyết duy nhất 1 chữ Hán; Pinyin gợi ý có thanh điệu chuẩn xác đặt tương ứng ngay trên vị trí chỗ trống. |
| **BR04** | Đếm ký tự & Chặn nhập | Hệ thống hiển thị bộ đếm ký tự thời gian thực (x/50) ở góc ô nhập; tự động khóa/chặn không cho nhập thêm khi đã đạt đủ 50 ký tự. |

## **16\. Chức năng Luyện thi HSK \- Sắp xếp 3 câu văn thành đoạn văn logic (HSK-READ-ORDER-SENTENCE: HSK Reading 3-Sentence Logic Ordering)**

### **16.1. Thông tin chung**

| Mã chức năng | HSK-READ-ORDER-SENTENCE |
| :---- | :---- |
| **Tên chức năng** | Sắp xếp 3 câu văn thành đoạn văn logic (HSK Reading 3-Sentence Logic Ordering) |
| **Tác nhân (Actor)** | Người học (Learner) |
| **Mục tiêu** | Sắp xếp 3 câu văn cho sẵn (A, B, C bị xáo trộn trật tự) thành một đoạn văn hoàn chỉnh có tính logic và mạch lạc |
| **Tiền điều kiện** | Người học đang làm bài thi hoặc luyện tập HSK tại phần Đọc hiểu (Phần 2: 10 câu) |
| **Hậu điều kiện** | Ghi nhận chuỗi thứ tự sắp xếp câu vào bài làm để chấm điểm |

### **16.2. Luồng chính (Main Flow)**

| Bước | Tác nhân | Hành động |
| :---- | :---- | :---- |
| **1** | Hệ thống | Hiển thị danh sách 10 câu hỏi; mỗi câu gồm 3 câu văn tiếng Trung (A, B, C) chưa được sắp xếp theo đúng trật tự. |
| **2** | Người học | Đọc hiểu ngữ nghĩa và phân tích mối liên hệ logic giữa 3 câu văn. |
| **3** | Người học | Sắp xếp 3 câu văn theo đúng thứ tự (ví dụ: BAC, CAB, ABC,...). |
| **4** | Hệ thống | Ghi nhận chuỗi thứ tự đã sắp xếp cho câu hỏi. |
| **5** | Người học | Chuyển sang câu hỏi tiếp theo hoặc nộp bài. |

### **16.3. Luồng rẽ nhánh & Ngoại lệ (Alternative & Exception Flows)**

| Mã luồng | Điều kiện | Hành động xử lý |
| :---- | :---- | :---- |
| **AF-01** | Thay đổi thứ tự câu | Người học điều chỉnh lại vị trí các câu đã sắp xếp trước khi nộp bài. |
| **AF-02** | Đặt lại câu (Reset) | Người học chọn làm lại để xóa thứ tự hiện tại và sắp xếp lại từ đầu. |
| **AF-03** | Chuyển câu hỏi | Người học chủ động chọn câu hỏi bất kỳ trong 10 câu để làm. |
| **EF-01** | Hết giờ làm bài | Hệ thống tự động thu bài và chấm điểm chuỗi thứ tự câu hiện tại. |

### **16.4. Ràng buộc dữ liệu (Validation Rules \- VR)**

| Mã VR | Trường dữ liệu | Ràng buộc | Thông báo lỗi |
| :---- | :---- | :---- | :---- |
| **VR01** | Số lượng câu sắp xếp | Bắt buộc phải sắp xếp đủ cả 3 câu A, B, C (không thiếu, không trùng lặp câu). | "Vui lòng sắp xếp đủ 3 câu văn" |
| **VR02** | Định dạng câu văn | 100% là chữ Hán giản thể chuẩn, hoàn toàn không có phiên âm Pinyin. | "Lỗi định dạng đề thi" |

### **16.5. Quy tắc nghiệp vụ (Business Rules \- BR)**

| Mã BR | Tên quy tắc | Nội dung quy tắc |
| :---- | :---- | :---- |
| **BR01** | Tính điểm chính xác | Điểm chỉ được tính khi chuỗi thứ tự 3 câu khớp chính xác 100% với đáp án chuẩn duy nhất của đề thi (không chấm điểm từng phần). |
| **BR02** | Tự động lưu bài | Tự động lưu chuỗi thứ tự câu ngay khi người học thực hiện thao tác sắp xếp. |
| **BR03** | Không kèm Pinyin | Toàn bộ các câu văn không hiển thị Pinyin nhằm đánh giá chính xác năng lực đọc hiểu và tư duy liên kết logic. |

## **17\. Chức năng Luyện thi HSK 4 \- Nhìn tranh và từ gợi ý viết câu hoàn chỉnh (HSK4-WRITE-IMAGE-PROMPT: HSK 4 Writing Image & Keyword Prompted Sentence)**

### **17.1. Thông tin chung**

| Mã chức năng | HSK4-WRITE-IMAGE-PROMPT |
| :---- | :---- |
| **Tên chức năng** | Nhìn tranh và từ gợi ý viết câu hoàn chỉnh (HSK 4 Writing Image & Keyword Prompted Sentence) |
| **Tác nhân (Actor)** | Người học (Learner) |
| **Mục tiêu** | Dùng tranh và từ gợi ý để viết câu tiếng Trung hoàn chỉnh đúng ngữ pháp và ngữ cảnh |
| **Tiền điều kiện** | Đang làm bài thi HSK 4 tại phần Viết (Phần 2: 5 câu) |
| **Hậu điều kiện** | Lưu câu trả lời vào bài làm để chấm điểm |

### **17.2. Luồng chính (Main Flow)**

| Bước | Tác nhân | Hành động |
| :---- | :---- | :---- |
| **1** | Hệ thống | Hiển thị câu hỏi gồm: 1 tranh, từ gợi ý và ô nhập. |
| **2** | Người học | Quan sát tranh, đọc từ gợi ý và nhập câu tiếng Trung hoàn chỉnh. |
| **3** | Hệ thống | Ghi nhận câu trả lời cho câu hỏi. |
| **4** | Người học | Chuyển câu tiếp theo hoặc nộp bài. |

### **17.3. Luồng rẽ nhánh & Ngoại lệ (Alternative & Exception Flows)**

| Mã luồng | Điều kiện | Hành động xử lý |
| :---- | :---- | :---- |
| **AF-01** | Sửa câu trả lời | Người học chỉnh sửa hoặc nhập lại câu văn trước khi nộp bài. |
| **AF-02** | Chuyển câu hỏi | Chọn câu hỏi bất kỳ trong 5 câu phần Viết để làm. |
| **EF-01** | Hết giờ làm bài | Tự động thu bài và chấm điểm các câu hiện có trong bài làm. |

### **17.4. Ràng buộc dữ liệu (Validation Rules \- VR)**

| Mã VR | Trường dữ liệu | Ràng buộc | Thông báo lỗi |
| :---- | :---- | :---- | :---- |
| **VR01** | Ký tự chữ Hán | 100% là chữ Hán giản thể kèm dấu câu (không dùng Pinyin/La-tinh). | "Vui lòng nhập bằng chữ Hán" |
| **VR02** | Độ dài tối đa | Giới hạn tối đa 50 ký tự (bao gồm chữ Hán và dấu câu); hệ thống chặn không cho nhập tiếp khi đạt giới hạn. | "Khóa không cho viết nữa" |

### **17.5. Quy tắc nghiệp vụ (Business Rules \- BR)**

| Mã BR | Tên quy tắc | Nội dung quy tắc |
| :---- | :---- | :---- |
| **BR01** | Tiêu chí chấm điểm | Điểm tính theo: dùng đúng từ gợi ý, đúng ngữ pháp/chính tả và phù hợp ngữ cảnh tranh. |
| **BR02** | Chấp nhận nhiều cách viết | Chấp nhận mọi cách diễn đạt đúng ngữ pháp, chứa từ gợi ý và đúng ngữ cảnh. |
| **BR03** | Tự động lưu | Lưu câu trả lời ngay khi người học thao tác nhập. |
| **BR04** | Không Pinyin | Đề bài và từ gợi ý không hiển thị Pinyin. |
| **BR05** | Đếm ký tự & Chặn nhập | Hệ thống hiển thị bộ đếm ký tự thời gian thực (x/50) ở góc ô nhập; tự động khóa/chặn không cho nhập thêm khi đã đạt đủ 50 ký tự. |

## **18\. Chức năng Luyện thi HSK \- Đọc đoạn văn và điền từ vào chỗ trống (HSK-READ-CLOZE: HSK Reading Cloze Test / Fill in the Blanks)**

### **18.1. Thông tin chung**

| Mã chức năng | HSK-READ-CLOZE |
| :---- | :---- |
| **Tên chức năng** | Đọc đoạn văn và điền từ vào chỗ trống (HSK Reading Cloze Test) |
| **Tác nhân (Actor)** | Người học (Learner) |
| **Mục tiêu** | Đọc hiểu ngữ cảnh đoạn văn và chọn từ/câu thích hợp nhất trong 4 phương án (A, B, C, D) để điền vào các vị trí đục lỗ |
| **Tiền điều kiện** | Người học đang làm bài thi hoặc luyện tập tại phần Đọc hiểu |
| **Hậu điều kiện** | Ghi nhận các phương án đã chọn vào bài làm để chấm điểm |

### **18.2. Luồng chính (Main Flow)**

| Bước | Tác nhân | Hành động |
| :---- | :---- | :---- |
| **1** | Hệ thống | Hiển thị đoạn văn chứa các vị trí đục lỗ được đánh số thứ tự kèm danh sách 4 phương án lựa chọn (A, B, C, D) tương ứng cho từng vị trí. |
| **2** | Người học | Đọc hiểu ngữ cảnh đoạn văn và chọn 1 đáp án (A, B, C hoặc D) phù hợp nhất cho mỗi vị trí đục lỗ. |
| **3** | Hệ thống | Ghi nhận đáp án đã chọn cho vị trí tương ứng. |
| **4** | Người học | Tiếp tục làm các vị trí khác, chuyển đoạn văn hoặc nộp bài. |

### **18.3. Luồng rẽ nhánh & Ngoại lệ (Alternative & Exception Flows)**

| Mã luồng | Điều kiện | Hành động xử lý |
| :---- | :---- | :---- |
| **AF-01** | Thay đổi lựa chọn | Người học chọn lại đáp án khác (A/B/C/D) cho vị trí đục lỗ bất kỳ trước khi nộp bài. |
| **AF-02** | Chuyển câu tùy ý | Người học chủ động chọn vị trí câu hỏi hoặc chuyển đến đoạn văn bất kỳ để làm bài. |
| **EF-01** | Hết giờ làm bài | Hệ thống tự động thu bài và chấm điểm các đáp án hiện có trong bài làm. |

### **18.4. Ràng buộc dữ liệu (Validation Rules \- VR)**

| Mã VR | Trường dữ liệu | Ràng buộc | Thông báo lỗi |
| :---- | :---- | :---- | :---- |
| **VR01** | Lựa chọn đáp án | Mỗi vị trí đục lỗ chỉ được chọn duy nhất 1 đáp án (A, B, C hoặc D). | "Vui lòng chọn 1 đáp án" |
| **VR02** | Định dạng đề thi | Chữ Hán chuẩn theo cấp độ thi, hoàn toàn không hiển thị phiên âm Pinyin. | "Lỗi định dạng đề thi" |

### **18.5. Quy tắc nghiệp vụ (Business Rules \- BR)**

| Mã BR | Tên quy tắc | Nội dung quy tắc |
| :---- | :---- | :---- |
| **BR01** | Tính điểm độc lập | Mỗi vị trí đục lỗ được tính điểm độc lập theo đáp án chuẩn duy nhất của đề thi (chấm đúng/sai theo từng câu, không phụ thuộc vào kết quả của các chỗ trống khác trong cùng đoạn văn). |
| **BR02** | Tự động lưu bài | Hệ thống tự động lưu lựa chọn ngay khi người học thực hiện thao tác chọn đáp án. |
| **BR03** | Không kèm Pinyin | Toàn bộ đoạn văn và các phương án lựa chọn không hiển thị Pinyin nhằm đánh giá chính xác năng lực đọc hiểu chữ Hán. |

## **19\. Chức năng Luyện thi HSK \- Đọc đoạn văn và chọn câu khớp nội dung (HSK-READ-PASSAGE-MATCH: HSK Reading Passage Consistency Selection)**

### **19.1. Thông tin chung**

| Mã chức năng | HSK-READ-PASSAGE-MATCH |
| :---- | :---- |
| **Tên chức năng** | Đọc đoạn văn và chọn câu khớp nội dung (HSK Reading Passage Consistency Selection) |
| **Tác nhân (Actor)** | Người học (Learner) |
| **Mục tiêu** | Đọc hiểu đoạn văn ngắn và chọn 1 câu trong 4 phương án (A, B, C, D) có nội dung đồng nhất/khớp nhất với đoạn văn |
| **Tiền điều kiện** | Người học đang làm bài thi hoặc luyện tập tại phần Đọc hiểu |
| **Hậu điều kiện** | Ghi nhận đáp án đã chọn vào bài làm để chấm điểm |

### **19.2. Luồng chính (Main Flow)**

| Bước | Tác nhân | Hành động |
| :---- | :---- | :---- |
| **1** | Hệ thống | Hiển thị danh sách các câu hỏi; mỗi câu gồm 1 đoạn văn ngắn kèm 4 phương án lựa chọn (A, B, C, D). |
| **2** | Người học | Đọc hiểu thông tin đoạn văn, đối chiếu với 4 phương án và chọn 1 đáp án (A, B, C hoặc D) đúng/khớp nhất. |
| **3** | Hệ thống | Ghi nhận đáp án đã chọn cho câu hỏi tương ứng. |
| **4** | Người học | Tiếp tục làm các câu hỏi khác hoặc nộp bài. |

### **19.3. Luồng rẽ nhánh & Ngoại lệ (Alternative & Exception Flows)**

| Mã luồng | Điều kiện | Hành động xử lý |
| :---- | :---- | :---- |
| **AF-01** | Thay đổi lựa chọn | Người học chọn lại đáp án khác (A/B/C/D) cho câu hỏi bất kỳ trước khi nộp bài. |
| **AF-02** | Chuyển câu tùy ý | Người học chủ động bấm chọn câu hỏi bất kỳ trong danh sách để làm trước. |
| **EF-01** | Hết giờ làm bài | Hệ thống tự động khóa thao tác, thu bài và chấm điểm các câu trả lời hiện có trong bài làm. |

### **19.4. Ràng buộc dữ liệu (Validation Rules \- VR)**

| Mã VR | Trường dữ liệu | Ràng buộc | Thông báo lỗi |
| :---- | :---- | :---- | :---- |
| **VR01** | Lựa chọn đáp án | Mỗi câu hỏi chỉ được chọn duy nhất 1 đáp án (A, B, C hoặc D). | "Vui lòng chọn 1 đáp án" |
| **VR02** | Định dạng đề thi | Đoạn văn và 4 đáp án 100% là chữ Hán giản thể chuẩn, hoàn toàn không có phiên âm Pinyin. | "Lỗi định dạng đề thi" |

### **19.5. Quy tắc nghiệp vụ (Business Rules \- BR)**

| Mã BR | Tên quy tắc | Nội dung quy tắc |
| :---- | :---- | :---- |
| **BR01** | Tính điểm chính xác | Điểm chỉ được tính khi đáp án lựa chọn khớp chính xác 100% với đáp án chuẩn duy nhất của đề thi. |
| **BR02** | Tự động lưu bài | Hệ thống tự động lưu lựa chọn ngay khi người học thực hiện thao tác bấm chọn đáp án. |
| **BR03** | Không kèm Pinyin | Toàn bộ đoạn văn và các phương án nhận định không hiển thị Pinyin để đánh giá chính xác năng lực đọc hiểu chuyên sâu. |

## **20\. Chức năng Luyện thi HSK \- Đọc bài đọc dài và trả lời câu hỏi trắc nghiệm (HSK-READ-PASSAGE-QUIZ: HSK Reading Long Passage Comprehension Quiz)**

### **20.1. Thông tin chung**

| Mã chức năng | HSK-READ-PASSAGE-QUIZ |
| :---- | :---- |
| **Tên chức năng** | Đọc bài đọc dài và trả lời câu hỏi trắc nghiệm (HSK Reading Long Passage Comprehension Quiz) |
| **Tác nhân (Actor)** | Người học (Learner) |
| **Mục tiêu** | Đọc hiểu nội dung toàn diện của bài đọc dài và trả lời chính xác các câu hỏi trắc nghiệm 4 lựa chọn (A, B, C, D) đi kèm |
| **Tiền điều kiện** | Người học đang làm bài thi hoặc luyện tập tại phần Đọc hiểu |
| **Hậu điều kiện** | Ghi nhận các đáp án đã chọn vào bài làm để chấm điểm |

### **20.2. Luồng chính (Main Flow)**

| Bước | Tác nhân | Hành động |
| :---- | :---- | :---- |
| **1** | Hệ thống | Hiển thị bài đọc dài (kèm tranh minh họa nếu có) và danh sách các câu hỏi trắc nghiệm liên quan; mỗi câu gồm 4 phương án lựa chọn (A, B, C, D). |
| **2** | Người học | Đọc hiểu nội dung bài đọc, phân tích câu hỏi và chọn 1 đáp án (A, B, C hoặc D) cho từng câu hỏi. |
| **3** | Hệ thống | Ghi nhận đáp án đã chọn cho câu hỏi tương ứng. |
| **4** | Người học | Tiếp tục trả lời các câu hỏi khác trong cùng bài đọc, chuyển sang bài đọc tiếp theo hoặc nộp bài. |

### **20.3. Luồng rẽ nhánh & Ngoại lệ (Alternative & Exception Flows)**

| Mã luồng | Điều kiện | Hành động xử lý |
| :---- | :---- | :---- |
| **AF-01** | Thay đổi lựa chọn | Người học chọn lại đáp án khác (A/B/C/D) cho câu hỏi bất kỳ trước khi nộp bài. |
| **AF-02** | Chuyển câu tùy ý | Người học chủ động chọn câu hỏi bất kỳ trong nhóm hoặc chuyển giữa các bài đọc để làm trước. |
| **EF-01** | Hết giờ làm bài | Hệ thống tự động khóa thao tác, thu bài và chấm điểm các câu trả lời hiện có trong bài làm. |

### **20.4. Ràng buộc dữ liệu (Validation Rules \- VR)**

| Mã VR | Trường dữ liệu | Ràng buộc | Thông báo lỗi |
| :---- | :---- | :---- | :---- |
| **VR01** | Lựa chọn đáp án | Mỗi câu hỏi trắc nghiệm chỉ được chọn duy nhất 1 đáp án (A, B, C hoặc D). | "Vui lòng chọn 1 đáp án" |
| **VR02** | Định dạng đề thi | Bài đọc và các câu hỏi/đáp án 100% là chữ Hán giản thể chuẩn, hoàn toàn không hiển thị phiên âm Pinyin. | "Lỗi định dạng đề thi" |

### **20.5. Quy tắc nghiệp vụ (Business Rules \- BR)**

| Mã BR | Tên quy tắc | Nội dung quy tắc |
| :---- | :---- | :---- |
| **BR01** | Tính điểm độc lập | Mỗi câu hỏi trắc nghiệm được tính điểm độc lập theo đáp án chuẩn duy nhất của đề thi (chấm đúng/sai theo từng câu, không phụ thuộc vào các câu hỏi khác trong cùng bài đọc). |
| **BR02** | Tự động lưu bài | Hệ thống tự động lưu lựa chọn ngay khi người học thực hiện thao tác bấm chọn đáp án. |
| **BR03** | Không kèm Pinyin | Toàn bộ bài đọc dài, câu hỏi và các phương án lựa chọn không hiển thị Pinyin để đánh giá chính xác năng lực đọc hiểu chuyên sâu. |

## **21\. Chức năng Luyện thi HSK 5 \- Dùng từ gợi ý viết đoạn văn ngắn (HSK5-WRITE-WORDS-PASSAGE: HSK 5 Writing Passage from Given Keywords)**

### **21.1. Thông tin chung**

| Mã chức năng | HSK5-WRITE-WORDS-PASSAGE |
| :---- | :---- |
| **Tên chức năng** | Dùng từ gợi ý viết đoạn văn ngắn (HSK 5 Writing Passage from Given Keywords) |
| **Tác nhân (Actor)** | Người học (Learner) |
| **Mục tiêu** | Dùng các từ gợi ý cho sẵn để viết một đoạn văn tiếng Trung ngắn (\~80 chữ) đúng ngữ pháp, liên kết logic |
| **Tiền điều kiện** | Người học đang làm bài thi hoặc luyện tập tại phần Viết HSK 5 |
| **Hậu điều kiện** | Lưu đoạn văn đã viết vào bài làm để chấm điểm |

### **21.2. Luồng chính (Main Flow)**

| Bước | Tác nhân | Hành động |
| :---- | :---- | :---- |
| **1** | Hệ thống | Hiển thị danh sách các từ gợi ý cho sẵn và ô nhập văn bản kèm bộ đếm ký tự thời gian thực. |
| **2** | Người học | Đọc các từ gợi ý và nhập đoạn văn tiếng Trung (\~80 chữ Hán kèm dấu câu). |
| **3** | Hệ thống | Ghi nhận nội dung và tự động khóa ô nhập khi đạt tối đa 100 ký tự. |
| **4** | Người học | Chỉnh sửa nội dung, chuyển câu tiếp theo hoặc nộp bài. |

### **21.3. Luồng rẽ nhánh & Ngoại lệ (Alternative & Exception Flows)**

| Mã luồng | Điều kiện | Hành động xử lý |
| :---- | :---- | :---- |
| **AF-01** | Chỉnh sửa bài viết | Người học xóa, thêm hoặc sửa lại nội dung đoạn văn trước khi nộp bài. |
| **AF-02** | Chuyển câu tùy ý | Người học chủ động chọn câu hỏi bất kỳ trong phần Viết để làm bài. |
| **EF-01** | Hết giờ làm bài | Hệ thống tự động khóa ô nhập, thu bài và chấm điểm đoạn văn hiện có. |

### **21.4. Ràng buộc dữ liệu (Validation Rules \- VR)**

| Mã VR | Trường dữ liệu | Ràng buộc | Thông báo lỗi |
| :---- | :---- | :---- | :---- |
| **VR01** | Định dạng ký tự | 100% là chữ Hán giản thể kèm dấu câu (không dùng chữ La-tinh/Pinyin). | "Vui lòng nhập bằng chữ Hán" |
| **VR02** | Độ dài tối đa | Giới hạn tối đa 100 ký tự (bao gồm chữ Hán và dấu câu); hệ thống chặn không cho nhập tiếp khi đạt giới hạn. | Không báo lỗi, chặn không cho người dùng nhập tiếp |

### **21.5. Quy tắc nghiệp vụ (Business Rules \- BR)**

| Mã BR | Tên quy tắc | Nội dung quy tắc |
| :---- | :---- | :---- |
| **BR01** | Tiêu chí chấm điểm | Đánh giá dựa trên: sử dụng đủ các từ gợi ý, đúng ngữ pháp/chính tả, dung lượng phù hợp (\~80 chữ) và tính liên kết mạch lạc của đoạn văn. |
| **BR02** | Đếm ký tự & Chặn nhập | Hệ thống hiển thị bộ đếm ký tự thời gian thực (x/100) ở góc ô nhập; tự động khóa/chặn không cho nhập thêm khi đã đạt đủ 100 ký tự. |
| **BR03** | Tự động lưu bài | Hệ thống tự động lưu nội dung đoạn văn ngay khi người học thực hiện thao tác nhập. |
| **BR04** | Không kèm Pinyin | Danh sách từ gợi ý không hiển thị Pinyin để đánh giá chính xác năng lực nhận diện và ứng dụng chữ Hán. |

## **22\. Chức năng Luyện thi HSK 5 \- Nhìn tranh viết đoạn văn ngắn (HSK5-WRITE-IMAGE-PASSAGE: HSK 5 Writing Passage from Given Image)**

### **22.1. Thông tin chung**

| Mã chức năng | HSK5-WRITE-IMAGE-PASSAGE |
| :---- | :---- |
| **Tên chức năng** | Nhìn tranh viết đoạn văn ngắn (HSK 5 Writing Passage from Given Image) |
| **Tác nhân (Actor)** | Người học (Learner) |
| **Mục tiêu** | Quan sát tranh minh họa và viết một đoạn văn tiếng Trung ngắn (\~80 chữ) đúng ngữ pháp, logic và phù hợp ngữ cảnh tranh |
| **Tiền điều kiện** | Người học đang làm bài thi hoặc luyện tập tại phần Viết HSK 5 |
| **Hậu điều kiện** | Lưu đoạn văn đã viết vào bài làm để chấm điểm |

### **22.2. Luồng chính (Main Flow)**

| Bước | Tác nhân | Hành động |
| :---- | :---- | :---- |
| **1** | Hệ thống | Hiển thị 1 bức tranh minh họa và ô nhập văn bản kèm bộ đếm ký tự thời gian thực. |
| **2** | Người học | Quan sát tranh, tư duy nội dung và nhập đoạn văn tiếng Trung (\~80 chữ Hán kèm dấu câu). |
| **3** | Hệ thống | Ghi nhận nội dung và tự động khóa ô nhập khi đạt tối đa 100 ký tự. |
| **4** | Người học | Chỉnh sửa nội dung, chuyển câu khác hoặc nộp bài. |

### **22.3. Luồng rẽ nhánh & Ngoại lệ (Alternative & Exception Flows)**

| Mã luồng | Điều kiện | Hành động xử lý |
| :---- | :---- | :---- |
| **AF-01** | Chỉnh sửa bài viết | Người học xóa, thêm hoặc sửa lại nội dung đoạn văn trước khi nộp bài. |
| **AF-02** | Chuyển câu tùy ý | Người học chủ động chọn câu hỏi bất kỳ trong phần Viết để làm bài. |
| **EF-01** | Hết giờ làm bài | Hệ thống tự động khóa ô nhập, thu bài và chấm điểm đoạn văn hiện có. |

### **22.4. Ràng buộc dữ liệu (Validation Rules \- VR)**

| Mã VR | Trường dữ liệu | Ràng buộc | Thông báo lỗi |
| :---- | :---- | :---- | :---- |
| **VR01** | Định dạng ký tự | 100% là chữ Hán giản thể kèm dấu câu (không dùng chữ La-tinh/Pinyin). | "Vui lòng nhập bằng chữ Hán" |
| **VR02** | Độ dài tối đa | Giới hạn tối đa 100 ký tự (bao gồm chữ Hán và dấu câu); hệ thống chặn không cho nhập tiếp khi đạt giới hạn. | Không báo lỗi, chặn không cho người dùng nhập tiếp |

### **22.5. Quy tắc nghiệp vụ (Business Rules \- BR)**

| Mã BR | Tên quy tắc | Nội dung quy tắc |
| :---- | :---- | :---- |
| **BR01** | Tiêu chí chấm điểm | Đánh giá dựa trên: nội dung phù hợp với tranh, đúng ngữ pháp/chính tả, dung lượng phù hợp (\~80 chữ) và tính liên kết mạch lạc của đoạn văn. |
| **BR02** | Chấp nhận nhiều cách viết | Chấp nhận mọi hướng triển khai nội dung đúng ngữ cảnh bức tranh, đảm bảo ngữ pháp và diễn đạt tự nhiên. |
| **BR03** | Đếm ký tự & Chặn nhập | Hệ thống hiển thị bộ đếm ký tự thời gian thực (x/100) ở góc ô nhập; tự động khóa/chặn không cho nhập thêm khi đã đạt đủ 100 ký tự. |
| **BR04** | Tự động lưu bài | Hệ thống tự động lưu nội dung đoạn văn ngay khi người học thực hiện thao tác nhập. |

## **23\. Chức năng Luyện thi HSK 6 \- Đọc hiểu Phần 1: Tìm câu chứa lỗi sai (HSK6-READ-FIND-ERROR: HSK 6 Reading Part 1: Identify Error Sentence)**

### **23.1. Thông tin chung**

| Mã chức năng | HSK6-READ-FIND-ERROR |
| :---- | :---- |
| **Tên chức năng** | Đọc hiểu Phần 1: Tìm câu sai (HSK 6 Reading Part 1: Identify Error Sentence) |
| **Tác nhân (Actor)** | Người học (Learner) |
| **Mục tiêu** | Đọc 4 câu văn (A, B, C, D) và chọn đúng 1 câu chứa lỗi sai |
| **Tiền điều kiện** | Đang làm bài thi/luyện tập Đọc hiểu HSK 6 |
| **Hậu điều kiện** | Lưu đáp án đã chọn của người học |

### **23.2. Luồng chính (Main Flow)**

| Bước | Tác nhân | Hành động |
| :---- | :---- | :---- |
| **1** | Hệ thống | Hiển thị nội dung 4 câu văn tương ứng 4 phương án A, B, C, D. |
| **2** | Người học | Đọc và chạm/click chọn câu chứa lỗi sai. |
| **3** | Hệ thống | Đánh dấu chọn (highlight) và tự động lưu đáp án. |
| **4** | Người học | Chuyển sang câu khác hoặc hoàn thành bài làm. |

### **23.3. Luồng rẽ nhánh & Ngoại lệ (Alternative & Exception Flows)**

| Mã luồng | Điều kiện | Hành động xử lý |
| :---- | :---- | :---- |
| **AF-01** | Đổi đáp án | Người học chọn phương án khác; hệ thống cập nhật lựa chọn mới. |
| **AF-02** | Bỏ chọn | Người học chạm lại vào phương án đang chọn; hệ thống hủy chọn. |
| **AF-03** | Chuyển câu tùy ý | Người học chủ động chọn câu hỏi bất kỳ trong danh sách. |
| **EF-01** | Hết giờ làm bài | Hệ thống tự động thu bài và chấm điểm theo các đáp án hiện có. |

### **23.4. Ràng buộc dữ liệu (Validation Rules \- VR)**

| Mã VR | Trường dữ liệu | Ràng buộc | Thông báo lỗi |
| :---- | :---- | :---- | :---- |
| **VR01** | Chọn đáp án | Chọn tối đa 1 trong 4 phương án (A, B, C, D). | Không báo lỗi, hệ thống ghi đè lựa chọn mới |

### **23.5. Quy tắc nghiệp vụ (Business Rules \- BR)**

| Mã BR | Tên quy tắc | Nội dung quy tắc |
| :---- | :---- | :---- |
| **BR01** | Đáp án duy nhất | Mỗi câu hỏi chỉ có đúng 1 câu chứa lỗi sai; 3 câu còn lại chuẩn ngữ pháp. |
| **BR02** | Trạng thái hiển thị | Phương án được chọn chuyển sang trạng thái Selected (highlight màu nền/viền). |
| **BR03** | Quy tắc chấm điểm | Chọn đúng được điểm; chọn sai hoặc bỏ trống nhận 0 điểm, không bị trừ điểm. |
| **BR04** | Tự động lưu bài | Hệ thống tự động lưu lựa chọn ngay khi người học thao tác. |

## **24\. Chức năng Luyện thi HSK 6 \- Đọc hiểu Phần 2: Điền bộ từ vào đoạn văn (HSK6-READ-CLOZE-GROUP: HSK 6 Reading Part 2: Cloze Test with Word Groups)**

### **24.1. Thông tin chung**

| Mã chức năng | HSK6-READ-CLOZE-GROUP |
| :---- | :---- |
| **Tên chức năng** | Đọc hiểu Phần 2: Điền bộ từ vào đoạn văn (HSK 6 Reading Part 2: Cloze Test with Word Groups) |
| **Tác nhân (Actor)** | Người học (Learner) |
| **Mục tiêu** | Đọc đoạn văn có 3–5 chỗ trống và chọn 1 phương án chứa trọn bộ cụm từ phù hợp nhất |
| **Tiền điều kiện** | Đang làm bài thi/luyện tập Đọc hiểu HSK 6 |
| **Hậu điều kiện** | Lưu phương án bộ từ đã chọn của người học |

### **24.2. Luồng chính (Main Flow)**

| Bước | Tác nhân | Hành động |
| :---- | :---- | :---- |
| **1** | Hệ thống | Hiển thị đoạn văn chứa các chỗ trống và 4 phương án A, B, C, D (mỗi phương án là 1 dãy từ tương ứng theo thứ tự). |
| **2** | Người học | Đọc đoạn văn, phân tích ngữ cảnh và chạm/click chọn 1 phương án (A, B, C hoặc D). |
| **3** | Hệ thống | Ghi nhận và tự động lưu đáp án đã chọn. |
| **4** | Người học | Chuyển sang câu khác hoặc hoàn thành bài thi. |

### **24.3. Luồng rẽ nhánh & Ngoại lệ (Alternative & Exception Flows)**

| Mã luồng | Điều kiện | Hành động xử lý |
| :---- | :---- | :---- |
| **AF-01** | Đổi phương án | Người học chọn phương án khác; hệ thống cập nhật lựa chọn mới. |
| **AF-02** | Bỏ chọn | Người học chạm lại vào phương án đang chọn; hệ thống hủy chọn. |
| **AF-03** | Chuyển câu tùy ý | Người học chủ động chọn câu hỏi bất kỳ trong danh sách. |
| **EF-01** | Hết giờ làm bài | Hệ thống tự động thu bài và chấm điểm theo các đáp án hiện có. |

### **24.4. Ràng buộc dữ liệu (Validation Rules \- VR)**

| Mã VR | Trường dữ liệu | Ràng buộc | Thông báo lỗi |
| :---- | :---- | :---- | :---- |
| **VR01** | Chọn đáp án | Chọn tối đa 1 phương án nguyên bộ từ (Single-choice: A, B, C hoặc D). | Không báo lỗi, hệ thống ghi đè lựa chọn mới |

### **24.5. Quy tắc nghiệp vụ (Business Rules \- BR)**

| Mã BR | Tên quy tắc | Nội dung quy tắc |
| :---- | :---- | :---- |
| **BR01** | Chọn nguyên bộ từ | Người học chọn 1 phương án đại diện cho toàn bộ các vị trí trống trong câu, không chọn rời rạc từng vị trí. |
| **BR02** | Quy tắc chấm điểm | Chọn đúng phương án nhận trọn điểm của câu; chọn sai hoặc bỏ trống nhận 0 điểm, không bị trừ điểm. |
| **BR03** | Tự động lưu bài | Hệ thống tự động lưu lựa chọn ngay khi người học thao tác. |

## **25\. Chức năng Luyện thi HSK 6 \- Đọc hiểu: Điền câu vào chỗ trống (HSK6-READ-FILL-SENTENCE: HSK 6 Reading: Fill Sentences into Paragraph Blanks)**

### **25.1. Thông tin chung**

| Mã chức năng | HSK6-READ-FILL-SENTENCE |
| :---- | :---- |
| **Tên chức năng** | Đọc hiểu: Điền câu vào chỗ trống (HSK 6 Reading: Fill Sentences into Paragraph Blanks) |
| **Tác nhân (Actor)** | Người học (Learner) |
| **Mục tiêu** | Đọc đoạn văn có các vị trí trống và chọn câu văn phù hợp từ các phương án gợi ý (A, B, C, D, E) để điền vào từng vị trí |
| **Tiền điều kiện** | Đang làm bài thi/luyện tập Đọc hiểu HSK 6 |
| **Hậu điều kiện** | Lưu các đáp án đã chọn cho từng vị trí trống trong đoạn văn |

### **25.2. Luồng chính (Main Flow)**

| Bước | Tác nhân | Hành động |
| :---- | :---- | :---- |
| **1** | Hệ thống | Hiển thị đoạn văn chứa các vị trí trống, danh sách các câu văn gợi ý (A, B, C, D, E) và bảng chọn đáp án cho từng vị trí. |
| **2** | Người học | Đọc đoạn văn, phân tích ngữ cảnh và chạm/click chọn 1 phương án (A, B, C, D hoặc E) cho từng vị trí trống. |
| **3** | Hệ thống | Ghi nhận và tự động lưu đáp án đã chọn cho từng câu hỏi. |
| **4** | Người học | Chuyển sang phần thi khác hoặc hoàn thành bài thi. |

### **25.3. Luồng rẽ nhánh & Ngoại lệ (Alternative & Exception Flows)**

| Mã luồng | Điều kiện | Hành động xử lý |
| :---- | :---- | :---- |
| **AF-01** | Đổi đáp án | Người học chọn phương án khác cho vị trí đang làm; hệ thống cập nhật lựa chọn mới. |
| **AF-02** | Bỏ chọn | Người học chạm lại vào phương án đang chọn; hệ thống hủy chọn câu đó. |
| **AF-03** | Chọn câu tùy ý | Người học chủ động làm bất kỳ vị trí trống nào trước mà không bắt buộc theo thứ tự. |
| **EF-01** | Hết giờ làm bài | Hệ thống tự động thu bài và chấm điểm theo các đáp án hiện có. |

### **25.4. Ràng buộc dữ liệu (Validation Rules \- VR)**

| Mã VR | Trường dữ liệu | Ràng buộc | Thông báo lỗi |
| :---- | :---- | :---- | :---- |
| **VR01** | Chọn đáp án mỗi câu | Mỗi vị trí trống chỉ được chọn tối đa 1 phương án duy nhất (A, B, C, D hoặc E). | Không báo lỗi, hệ thống ghi đè lựa chọn mới |

### **25.5. Quy tắc nghiệp vụ (Business Rules \- BR)**

| Mã BR | Tên quy tắc | Nội dung quy tắc |
| :---- | :---- | :---- |
| **BR01** | Cặp ghép logic | Các phương án gợi ý tương ứng ghép khớp chính xác với từng vị trí trống tạo thành đoạn văn hoàn chỉnh về ngữ pháp và ý nghĩa. |
| **BR02** | Chấm điểm độc lập | Mỗi vị trí trống được tính điểm độc lập; chọn đúng nhận điểm của câu đó, chọn sai hoặc bỏ trống nhận 0 điểm, không bị trừ điểm. |
| **BR03** | Tự động lưu bài | Hệ thống tự động lưu lựa chọn ngay khi người học thao tác trên từng câu. |

## **26\. Chức năng Luyện thi HSK 6 \- Viết tóm tắt bài văn (HSK6-WRITE-SUMMARY: HSK 6 Writing Narrative Summarization)**

### **26.1. Thông tin chung**

| Mã chức năng | HSK6-WRITE-SUMMARY |
| :---- | :---- |
| **Tên chức năng** | Viết tóm tắt bài văn (HSK 6 Writing: Narrative Summarization) |
| **Tác nhân (Actor)** | Người học (Learner) |
| **Mục tiêu** | Đọc bài văn gốc (\~1000 chữ) trong 10 phút, sau đó tự đặt tiêu đề và viết tóm tắt lại (\~400 chữ) trong 35 phút |
| **Tiền điều kiện** | Đang ở phần thi/luyện tập Viết HSK 6 |
| **Hậu điều kiện** | Lưu tiêu đề và đoạn văn tóm tắt vào bài làm để chấm điểm |

### **26.2. Luồng chính (Main Flow)**

| Bước | Tác nhân | Hành động |
| :---- | :---- | :---- |
| **1** | Hệ thống | Hiển thị bài văn gốc (\~1000 chữ) và đếm ngược 10 phút đọc bài (khóa sao chép). |
| **2** | Hệ thống | Hết 10 phút đọc (hoặc người học chuyển sớm), tự động ẩn bài văn gốc và mở giao diện viết (đếm ngược 35 phút). |
| **3** | Người học | Nhập Tiêu đề mới và viết nội dung tóm tắt (\~400 chữ) một cách khách quan. |
| **4** | Hệ thống | Tự động ghi nhận bài làm theo thời gian thực; thu bài khi người học nộp hoặc khi hết 35 phút. |

### **26.3. Luồng rẽ nhánh & Ngoại lệ (Alternative & Exception Flows)**

| Mã luồng | Điều kiện | Hành động xử lý |
| :---- | :---- | :---- |
| **AF-01** | Bắt đầu viết sớm | Người học hoàn thành đọc trước 10 phút; bấm chuyển ngay sang giao diện viết. |
| **AF-02** | Chỉnh sửa bài viết | Người học tự do chỉnh sửa tiêu đề và nội dung tóm tắt trong thời gian 35 phút viết bài. |
| **EF-01** | Hết 10 phút đọc | Hệ thống tự động thu hồi bài gốc, chuyển sang màn hình viết và không cho phép xem lại bài gốc. |
| **EF-02** | Hết 35 phút viết | Hệ thống tự động khóa ô nhập, lưu bài và chuyển sang màn hình hoàn thành. |

### **26.4. Ràng buộc dữ liệu (Validation Rules \- VR)**

| Mã VR | Trường dữ liệu | Ràng buộc | Thông báo lỗi |
| :---- | :---- | :---- | :---- |
| **VR01** | Tiêu đề bài viết | Bắt buộc nhập, tối đa 20 ký tự chữ Hán. | "Vui lòng nhập tiêu đề bài viết" |
| **VR02** | Định dạng ký tự | 100% là chữ Hán giản thể kèm dấu câu tiếng Trung. | "Vui lòng nhập bằng chữ Hán" |
| **VR03** | Độ dài bài tóm tắt | Dung lượng khoảng 400 chữ Hán (giới hạn tối đa 500 ký tự); hệ thống chặn không cho nhập tiếp khi đạt giới hạn. | Không báo lỗi, chặn không cho người dùng nhập tiếp |

### **26.5. Quy tắc nghiệp vụ (Business Rules \- BR)**

| Mã BR | Tên quy tắc | Nội dung quy tắc |
| :---- | :---- | :---- |
| **BR01** | Cơ chế 2 giai đoạn | Giai đoạn 1 (Đọc 10 phút): Khóa copy/ghi chép, bài đọc bị thu hồi hoàn toàn sau 10 phút; Giai đoạn 2 (Viết 35 phút): Mở ô nhập tiêu đề và bài tóm tắt kèm bộ đếm ký tự. |
| **BR02** | Yêu cầu nội dung tóm tắt | Phải tự đặt tiêu đề mới, thuật lại câu chuyện khách quan, giữ đúng cốt truyện chính và tuyệt đối không thêm quan điểm cá nhân. |
| **BR03** | Tiêu chí chấm điểm | Đánh giá dựa trên: tiêu đề phù hợp, nội dung tóm tắt đầy đủ ý chính, đúng ngữ pháp/chính tả và độ dài phù hợp (\~400 chữ). |
| **BR04** | Tự động lưu & Thu bài | Hệ thống tự động lưu bài theo thời gian thực và tự động thu bài ngay khi hết giờ. |

# **PHÂN HỆ VI. CỘNG ĐỒNG & THI ĐẤU (COMMUNITY & PVP)**

## **1\. Chức năng Xem Bảng xếp hạng (COM-01: View Leaderboard)**

### **1.1. Thông tin chung**

| Mã chức năng | COM-01 |
| :---- | :---- |
| **Tác nhân (Actor)** | Người học (Learner) |
| **Mục tiêu** | Hiển thị danh sách bảng xếp hạng người dùng được sắp xếp theo điểm kinh nghiệm (XP) |
| **Tiền điều kiện (Pre-condition)** | Người học đã đăng nhập vào ứng dụng và thiết bị có kết nối mạng |
| **Hậu điều kiện (Post-condition)** | Danh sách bảng xếp hạng được hiển thị trên màn hình Cộng đồng |

### **1.2. Luồng chính (Main Flow)**

| Bước | Tác nhân | Hành động |
| :---- | :---- | :---- |
| **1** | Người dùng | Chọn mục "Cộng đồng" trên thanh điều hướng dưới đáy. |
| **2** | Hệ thống | Lấy danh sách người dùng từ hệ thống (gồm: Tên, Level, XP, Streak, Vị trí thứ hạng và Biến động thứ hạng). |
| **3** | Hệ thống | Sắp xếp danh sách người dùng theo XP giảm dần. |
| **4** | Hệ thống | Hiển thị bảng xếp hạng ra màn hình. |
| **5** | Người dùng | Xem thông tin bảng xếp hạng. |
| **6** | \- | Use case kết thúc. |

### **1.3. Luồng rẽ nhánh & Ngoại lệ (Alternative & Exception Flows)**

| Mã luồng | Bước | Tác nhân | Điều kiện & Hành động xử lý |
| :---- | :---- | :---- | :---- |
| **AF-01** | Tại Bước 2 | Hệ thống | Không lấy được dữ liệu (Mất mạng / Lỗi máy chủ):• Hiển thị thông báo: "Không thể tải dữ liệu bảng xếp hạng" kèm nút thử lại.• Use case kết thúc. |
| **AF-02** | Tại Bước 2 | Hệ thống | Không có người dùng (Danh sách rỗng):• Hiển thị thông báo: "Chưa có dữ liệu người dùng trong bảng xếp hạng", danh sách hiển thị trống.• Use case kết thúc. |
| **AF-03** | Tại Bước 5 | Người dùng | Bấm nút nổi (FAB) "1v1 Thách Đấu": Hệ thống điều hướng người dùng sang Màn hình Thiết lập & Chọn chế độ Đấu 1vs1 (PVP-MODE-SELECT). Chuyển sang thực hiện Use Case PVP-MODE-SELECT. |

### **1.4. Ràng buộc dữ liệu (Validation Rules \- VR)**

| Mã VR | Trường dữ liệu | Ràng buộc kiểm tra | Thông báo lỗi hiển thị |
| :---- | :---- | :---- | :---- |
| **VR01** | Tổng điểm kinh nghiệm tích luỹ (Total XP) | • Là số nguyên không âm (\>= 0\)• Giá trị khởi tạo mặc định bằng 0 | "Dữ liệu điểm XP không hợp lệ" |
| **VR02** | Cấp bậc tài khoản (Account Level) | • Là số nguyên dương (\>= 1\) | "Dữ liệu cấp độ không hợp lệ" |
| **VR03** | Số ngày Streak | • Là số nguyên không âm (\>= 0\) | "Dữ liệu chuỗi ngày không hợp lệ" |
| **VR04** | Biến động thứ hạng (Delta) | • Là số nguyên (thể hiện số bậc tăng/giảm hoặc 0 nếu giữ nguyên) | "Dữ liệu biến động thứ hạng không hợp lệ" |

### **1.5. Quy tắc nghiệp vụ (Business Rules \- BR)**

| Mã BR | Tên quy tắc | Nội dung chi tiết |
| :---- | :---- | :---- |
| **BR01** | Sắp xếp thứ hạng | Danh sách người dùng được sắp xếp theo thứ tự giảm dần của điểm kinh nghiệm (XP). Người có XP cao nhất đứng vị trí số 1\. |
| **BR02** | Xử lý đồng điểm XP | Trường hợp các người dùng có cùng số điểm XP, hệ thống ưu tiên xếp trên cho người dùng có Cấp độ (Level) cao hơn. |
| **BR03** | Tính biến động thứ hạng | Biến động thứ hạng được tính bằng chênh lệch giữa vị trí thứ hạng trước đó và vị trí thứ hạng hiện tại của người dùng (tăng hạng, giảm hạng hoặc giữ nguyên hạng). |

## **2\. Chức năng Chọn chế độ Đấu 1vs1 (PVP-MODE-SELECT: 1vs1 Battle Mode Selection)**

### **2.1. Thông tin chung**

| Mã chức năng | PVP-MODE-SELECT |
| :---- | :---- |
| **Tên chức năng** | Thiết lập & Chọn chế độ Đấu 1vs1 (1vs1 Battle Mode Selection) |
| **Tác nhân (Actor)** | Người học (Learner) |
| **Mục tiêu** | Cho phép người dùng chọn chế độ thi đấu trước khi tìm trận |
| **Tiền điều kiện (Pre-condition)** | Người dùng bấm nút "1v1 Thách Đấu" từ màn hình Bảng xếp hạng |
| **Hậu điều kiện (Post-condition)** | Ghi nhận chế độ đã chọn và chuyển sang Use Case Tìm đối thủ (PVP-MATCH) |

### **2.2. Luồng chính (Main Flow)**

| Bước | Tác nhân | Hành động |
| :---: | :---- | :---- |
| **1** | Hệ thống | Hiển thị thông tin các chế độ đấu. |
| **2** | Người dùng | Chọn 1 chế độ đấu. |
| **3** | Người dùng | Bấm nút "Bắt đầu tìm đối thủ". |
| **4** | Hệ thống | Chuyển sang Use Case Tìm đối thủ (PVP-MATCH). |
| **5** | \- | Use case kết thúc. |

### **2.3. Luồng rẽ nhánh & Ngoại lệ (Alternative & Exception Flows)**

| Mã luồng | Bước | Tác nhân | Điều kiện & Hành động xử lý |
| :---: | :---: | :---- | :---- |
| **AF-01** | Bước 1 | Người dùng | Bấm nút Quay lại (Back): Quay lại màn hình Bảng xếp hạng. Use case kết thúc. |
| **AF-02** | Bước 3 | Người dùng | Bấm "Tạo phòng với bạn bè": Chuyển sang luồng Tạo phòng thi đấu riêng. |
| **AF-03** | Bước 3 | Hệ thống | Không đủ Đá quý (Gems): Hiển thị thông báo nạp thêm hoặc chờ hồi phục. |

### **2.4. Ràng buộc dữ liệu (Validation Rules \- VR)**

| Mã VR | Trường dữ liệu | Ràng buộc kiểm tra | Thông báo lỗi hiển thị |
| :---: | :---- | :---- | :---- |
| **VR01** | Chế độ thi đấu (mode\_id) | Bắt buộc chọn đúng 1 trong 7 chế độ: MIXED\_RANK, VOCABULARY, PINYIN, PRONUNCIATION, LISTENING, TONES, GRAMMAR. | "Vui lòng chọn một chế độ thi đấu" |
| **VR02** | Trạng thái mở khóa | Chế độ đấu phải ở trạng thái mở khóa đối với tài khoản. | "Chế độ chưa được mở khóa" |

### **2.5. Quy tắc nghiệp vụ (Business Rules \- BR)**

| Mã BR | Tên quy tắc | Nội dung chi tiết |
| ----- | :---- | :---- |
| **BR01** | Danh mục 7 chế độ đấu | Gồm 7 chế độ: 1\. Đấu Hỗn Hợp (Rank), 2\. Từ vựng, 3\. Pinyin, 4\. Phát âm, 5\. Luyện nghe, 6\. Thanh điệu, 7\. Ngữ pháp. |
| **BR02** | Lựa chọn độc bản | Chỉ được chọn duy nhất 1 chế độ tại một thời điểm. Mặc định là "Đấu Hỗn Hợp (Rank)". |
| **BR03** | Ghép trận theo chế độ | Tham số chế độ đã chọn được gửi sang hệ thống Matchmaking để ghép đôi đúng đối thủ cùng chế độ. |
| BR04 | Ánh xạ 7 chế độ về 2 loại trận đấu | • Trận Phát âm (PVP-VOICE-BATTLE): PRONUNCIATION, TONES, PINYIN.• Trận Trắc nghiệm (PVP-QUIZ-SELECT): MIXED\_RANK, VOCABULARY, LISTENING, GRAMMAR. |

## **3\. Chức năng Tìm đối thủ đấu 1vs1 (PVP-MATCH: 1vs1 Matchmaking & Opponent Search)**

### **3.1. Thông tin chung**

| Mã chức năng | PVP-MATCH |
| :---- | :---- |
| **Tên chức năng** | Tìm đối thủ đấu 1vs1 (1vs1 Matchmaking & Opponent Search) |
| **Tác nhân (Actor)** | Người học (Learner) |
| **Mục tiêu** | Người dùng đã chọn chế độ thi đấu tại PVP-MODE-SELECT và bấm "Bắt đầu tìm đối thủ" |
| **Tiền điều kiện (Pre-condition)** | Người học đã đăng nhập và đã chọn một trong 7 chế độ thi đấu (theo PVP-MODE-SELECT). |
| **Hậu điều kiện (Post-condition)** | Ghép đôi thành công, khởi tạo phòng đấu và chuyển sang phiên thi đấu 1vs1 |

### **3.2. Luồng chính (Main Flow)**

| Bước | Tác nhân | Hành động |
| :---- | :---- | :---- |
| **1** | Hệ thống | Nhận thông tin người chơi và mode\_id đã chọn từ PVP-MODE-SELECT, đưa người dùng vào hàng chờ Matchmaking. |
| **2** | Hệ thống | Đưa người dùng vào hàng chờ tìm kiếm đối thủ. |
| **3** | Hệ thống | Kích hoạt thuật toán Matchmaking tự động tìm kiếm đối thủ phù hợp dựa trên 6 tiêu chí: Level, HSK, Điểm XP, Kết quả thi đấu, Trình độ từ vựng và Khả năng phát âm. |
| **4** | Hệ thống | Tìm thấy đối thủ tương xứng, hiển thị thông tin đối thủ (Tên, Avatar, Level, Rank) và đếm ngược vào trận. |
| **5** | Hệ thống | Khởi tạo phòng đấu và chuyển sang Use Case thi đấu tương ứng (PVP-VOICE-BATTLE hoặc PVP-QUIZ-SELECT). |
| **6** | \- | Use case kết thúc. |

### **3.3. Luồng rẽ nhánh & Ngoại lệ (Alternative & Exception Flows)**

| Mã luồng | Bước | Tác nhân | Điều kiện & Hành động xử lý |
| :---- | :---- | :---- | :---- |
| **AF-01** | Bước 3 | Hệ thống | Không tìm thấy đối thủ sau thời gian chờ (Timeout 20 giây):• Hệ thống thông báo: "Không tìm thấy đối thủ phù hợp lúc này. Vui lòng thử lại sau".• Tự động dừng tìm kiếm và đưa người dùng quay lại màn hình trước.• Use case kết thúc. |
| **AF-02** | Bước 3 | Người dùng | Hủy tìm kiếm: Người dùng bấm "Hủy tìm trận" ➔ Hệ thống rút người dùng khỏi hàng chờ và dừng Use case. |
| **EF-01** | Bước 3 | Hệ thống | Mất kết nối mạng: Thông báo lỗi kết nối mạng, tự động hủy hàng chờ và dừng Use case. |

### **3.4. Ràng buộc dữ liệu (Validation Rules \- VR)**

| Mã VR | Trường dữ liệu | Ràng buộc kiểm tra | Thông báo lỗi hiển thị |
| :---- | :---- | :---- | :---- |
| **VR01** | Hồ sơ năng lực | Người dùng phải có đầy đủ 6 chỉ số: Level, HSK, XP, Kết quả thi đấu, Điểm từ vựng, Điểm phát âm AI. | "Dữ liệu người chơi không hợp lệ" |
| **VR02** | Thời gian chờ tối đa | Giới hạn tìm kiếm tối đa 20 giây trước khi tự động dừng tìm kiếm. | \- |
| **VR03** | Ngưỡng chênh lệch trình độ | Ngưỡng chênh lệch năng lực khởi tạo giữa 2 người chơi là ± 15%; hệ thống được phép nới rộng tối đa thêm ± 10% theo BR02 (tổng ngưỡng chênh lệch tối đa là ± 25%). | \- |
| VR04 | Vé thi đấu | Người chơi phải có ít nhất 1 Vé thi đấu, hoặc đủ Gems để mua vé, trước khi vào trận (tham chiếu HOME-02 BR03). | "Bạn không đủ Vé thi đấu hoặc Gems để vào trận" |

### **3.5. Quy tắc nghiệp vụ (Business Rules \- BR)**

| Mã BR | Tên quy tắc | Nội dung chi tiết |
| :---- | :---- | :---- |
| **BR01** | Thuật toán Matchmaking 6 tiêu chí | Hệ thống tự động ghép trận dựa trên trọng số 6 tiêu chí năng lực:1\. Level: Cấp bậc tài khoản hiện tại.2\. HSK: Trình độ HSK đang học (HSK 1 đến HSK 6).3\. Điểm XP: Tổng điểm kinh nghiệm tích lũy.4\. Kết quả thi đấu: Tỷ lệ thắng/thua và điểm xếp hạng trong các trận đấu 1vs1 trước đó.5\. Trình độ từ vựng: Số lượng từ vựng đã tích lũy và thành thạo.6\. Khả năng phát âm: Điểm đánh giá phát âm trung bình từ mô-đun AI. |
| **BR02** | Mở rộng biên độ tìm kiếm động | Nếu sau 10 giây chưa tìm thấy đối thủ, hệ thống tự động nới rộng biên độ chênh lệch năng lực thêm ± 10% để tăng tốc độ ghép trận. |
| **BR03** | Dừng tìm kiếm khi hết thời gian (Timeout) | Sau 20 giây tìm kiếm nếu không tìm thấy người chơi thực tế phù hợp, hệ thống tự động dừng hàng chờ và kết thúc phiên tìm kiếm. |

## **4\. Chức năng Đấu 1vs1 \- Chọn đáp án câu hỏi (PVP-QUIZ-SELECT: 1vs1 Battle Quiz Answering)**

### **4.1. Thông tin chung**

| Mã chức năng | PVP-QUIZ-SELECT |
| :---- | :---- |
| **Tác nhân (Actor)** | 2 Người chơi (Player 1 & Player 2\) |
| **Mục tiêu** | Thi đấu trắc nghiệm thời gian thực, ẩn lựa chọn tránh lộ đáp án và cộng điểm cho người trả lời đúng |
| **Tiền điều kiện (Pre-condition)** | Ghép trận thành công và kết nối phòng đấu 1vs1 ổn định |
| **Hậu điều kiện (Post-condition)** | Ghi nhận kết quả, cộng điểm và chuyển sang câu tiếp theo hoặc tổng kết trận đấu |

### **4.2. Luồng chính (Main Flow)**

| Bước | Tác nhân | Hành động |
| :---- | :---- | :---- |
| 1 | Hệ thống | Hiển thị câu hỏi, các đáp án lựa chọn và bắt đầu đếm ngược thời gian (10–15s). |
| 2 | Người chơi | Bấm chọn một đáp án. |
| 3 | Hệ thống | Lưu đáp án, ghi nhận mốc thời gian và hiển thị trạng thái chờ (ẩn lựa chọn với đối thủ). |
| 4 | Người chơi còn lại | Bấm chọn đáp án trước khi hết giờ. |
| 5 | Hệ thống | Khóa lượt chọn của cả hai người chơi. |
| 6 | Hệ thống | Công bố đáp án đúng và hiển thị lựa chọn của từng người chơi. |
| 7 | Hệ thống | Kiểm tra đáp án: Trả lời đúng được cộng điểm (cùng đúng thì người nhanh hơn được nhiều điểm hơn), trả lời sai không được cộng điểm. |
| 8 | Hệ thống | Cập nhật tổng điểm và chuyển sang câu tiếp theo (hoặc kết thúc trận đấu). |
| 9 | \- | Use case kết thúc. |

### **4.3. Luồng rẽ nhánh & Ngoại lệ (Alternative & Exception Flows)**

| Mã luồng | Bước | Tác nhân | Điều kiện & Hành động xử lý |
| :---- | :---- | :---- | :---- |
| **AF-01** | Tại Bước 4 | Hệ thống | Hết giờ làm bài:• Người chơi chưa chọn kịp → Không được cộng điểm và mở kết quả ở Bước 6\. |
| **AF-02** | Tại Bước 7 | Hệ thống | Cùng trả lời đúng:• Người gửi đáp án nhanh hơn nhận được nhiều điểm hơn. |
| **AF-03** | Tại Bước 7 | Hệ thống | Cùng trả lời sai:• Cả hai người chơi đều không được cộng điểm. |
| **EF-01** | Tại Bước 2, 4 | Hệ thống | Mất kết nối mạng:• Đếm ngược chờ kết nối lại 10s → Quá 10s xử thua cho người bị ngắt kết nối. |
| **EF-02** | Tại Bước 1, 6 | Hệ thống | Lệch thời gian thực:• Tự động lấy mốc thời gian chuẩn từ máy chủ làm căn cứ so sánh thời gian. |

### **4.4. Ràng buộc dữ liệu (Validation Rules \- VR)**

| Mã VR | Trường dữ liệu | Ràng buộc dữ liệu | Thông báo hiển thị |
| :---- | :---- | :---- | :---- |
| VR01 | Lựa chọn đáp án | Bắt buộc chọn duy nhất 1 đáp án; đã chọn thì không đổi được (theo BR03). | "Vui lòng chọn một đáp án" |
| VR02 | Danh sách đáp án câu hỏi | • Bắt buộc có đáp án đúng và các phương án lựa chọn không trùng lặp nội dung.• Ẩn lựa chọn của người chơi với đối thủ cho đến khi cả hai chọn xong (theo BR01). | "Dữ liệu câu hỏi không hợp lệ" |
| VR03 | Mốc thời gian trả lời | • Bắt buộc ghi nhận mốc thời gian theo giờ máy chủ khi người chơi bấm chọn.• Thời gian đếm ngược mỗi câu từ 10 đến 15 giây. | "Dữ liệu thời gian không hợp lệ" |

### **4.5. Quy tắc nghiệp vụ (Business Rules \- BR)**

| Mã BR | Tên quy tắc | Nội dung chi tiết |
| :---- | :---- | :---- |
| **BR01** | Chống lộ đáp án | Người chọn trước chỉ thấy trạng thái 'Đang chờ đối thủ...'. Kết quả chỉ mở ra đồng thời khi cả hai đã chọn xong hoặc khi hết giờ. |
| **BR02** | Quy tắc tính điểm | • Trả lời đúng: Được cộng điểm.• Trả lời sai hoặc không chọn: Không được cộng điểm (0 điểm).• Cùng trả lời đúng: Người chọn nhanh hơn sẽ được cộng điểm nhiều hơn người chọn chậm. |
| **BR03** | Khóa lựa chọn | Mỗi người chơi chỉ được bấm chọn đáp án 1 lần duy nhất trong mỗi câu, không thể chọn lại. |

## **5\. Chức năng Đấu 1vs1 \- Luyện phát âm (PVP-VOICE-BATTLE: 1vs1 Pronunciation Battle)**

### **5.1. Thông tin chung**

| Mã chức năng | PVP-VOICE-BATTLE |
| :---- | :---- |
| **Tác nhân (Actor)** | 2 Người chơi (Player 1 & Player 2\) |
| **Mục tiêu** | Thi đấu phát âm câu tiếng Trung thời gian thực, AI chấm điểm chuẩn xác và cộng điểm cho người có điểm số cao hơn |
| **Tiền điều kiện (Pre-condition)** | Hai người chơi đã vào phòng đấu 1vs1 và cấp quyền Micro |
| **Hậu điều kiện (Post-condition)** | Ghi nhận điểm phát âm AI, cộng điểm cho người thắng vòng thi và chuyển sang câu tiếp theo hoặc tổng kết trận đấu |

### **5.2. Luồng chính (Main Flow)**

| Bước | Tác nhân | Hành động |
| :---- | :---- | :---- |
| 1 | Hệ thống | Hiển thị câu đề bài tiếng Trung kèm phiên âm Pinyin và nút nghe phát âm mẫu cho cả hai người chơi. |
| 2 | Người chơi | Bấm nút Micro và đọc câu tiếng Trung theo đề bài. |
| 3 | Hệ thống | Lưu bản thu âm, gửi AI chấm điểm và hiển thị trạng thái chờ (ẩn kết quả với đối thủ). |
| 4 | Người chơi còn lại | Bấm nút Micro và hoàn tất lượt thu âm. |
| 5 | Hệ thống | Khóa lượt thu âm của cả hai người chơi và tiếp nhận kết quả chấm điểm từ AI. |
| 6 | Hệ thống | Đồng thời công bố điểm số phát âm AI của từng người chơi. |
| 7 | Hệ thống | So sánh điểm AI: Cộng điểm cho người chơi có điểm phát âm cao hơn (hòa điểm thì cộng điểm cho cả hai). |
| 8 | Hệ thống | Cập nhật tổng điểm và chuyển sang câu tiếp theo (hoặc kết thúc trận đấu). |
| 9 | \- | Use case kết thúc. |

### **5.3. Luồng rẽ nhánh & Ngoại lệ (Alternative & Exception Flows)**

| Mã luồng | Bước | Tác nhân | Điều kiện & Hành động xử lý |
| :---- | :---- | :---- | :---- |
| **AF-01** | Tại Bước 2, 4 | Người chơi | Nghe phát âm mẫu:• Bấm nút nghe âm thanh → Hệ thống phát âm thanh chuẩn của câu đề bài. |
| **AF-02** | Tại Bước 7 | Hệ thống | Hòa điểm phát âm AI:• Cả hai người chơi bằng điểm nhau → Cùng được cộng điểm. |
| **AF-03** | Tại Bước 2, 4 | Hệ thống | Thu âm không có tiếng:• Không nhận diện được âm thanh → Ghi nhận 0 điểm cho lượt thi đó. |
| **EF-01** | Tại Bước 2, 4 | Hệ thống | Mất kết nối mạng:• Đếm ngược chờ kết nối lại 10s → Quá 10s xử thua cho người bị ngắt kết nối. |
| **EF-02** | Tại Bước 3, 5 | Hệ thống | Lỗi dịch vụ AI chấm điểm:• Tự động gửi lại yêu cầu chấm điểm để đảm bảo không mất kết quả thi đấu. |

### **5.4. Ràng buộc dữ liệu (Validation Rules \- VR)**

| Mã VR | Trường dữ liệu | Ràng buộc dữ liệu | Thông báo hiển thị |
| :---- | :---- | :---- | :---- |
| VR01 | Tệp ghi âm của người chơi | • Bắt buộc có tệp ghi âm hợp lệ (.mp3, .m4a, .aac, .wav; dung lượng ≤ 5MB) cho mỗi lượt thi.• Bản thu âm không được rỗng; nếu không nhận diện được âm thanh thì ghi nhận 0 điểm cho lượt thi đó. | "Không nhận diện được âm thanh. Vui lòng thu âm lại" |
| VR02 | Tệp âm thanh mẫu (đề bài) | • File .mp3, .m4a, .aac, .wav (dung lượng ≤ 5MB).• Người học được nghe lại không giới hạn số lần trong bài học. | "Không thể phát âm thanh" |
| VR03 | Điểm phát âm AI | • Là số nguyên trong khoảng từ 0 đến 100 cho mỗi người chơi.• Bắt buộc có kết quả chấm điểm của cả hai người chơi trước khi công bố (theo BR03). | "Không thể chấm điểm phát âm lúc này" |

### **5.5. Quy tắc nghiệp vụ (Business Rules \- BR)**

| Mã BR | Tên quy tắc | Nội dung chi tiết |
| :---- | :---- | :---- |
| **BR01** | Không tính yếu tố thời gian | Vòng thi phát âm không tính thời gian nói nhanh hay chậm, chỉ xét thuần túy theo điểm số phát âm chính xác do AI chấm. |
| **BR02** | Quy tắc cộng điểm thắng cuộc | • Người có điểm AI cao hơn: Được cộng điểm chiến thắng vòng thi.• Người có điểm AI thấp hơn: Không được cộng điểm.• Bằng điểm AI: Cả hai người chơi cùng được cộng điểm. |
| **BR03** | Chống lộ điểm số | Người hoàn thành trước chỉ thấy trạng thái 'Đang chờ đối thủ...'. Điểm số chỉ mở ra đồng thời khi cả hai đã thu âm xong. |

# **PHÂN HỆ VII. TRA CỨU TỪ ĐIỂN & DỊCH THUẬT (DICTIONARY & TRANSLATION)**

## **1\. Chức năng Quét ảnh Tra từ & Dịch câu bằng Camera (DICT-01: Camera OCR Dictionary & Sentence Translation)**

### **1.1. Thông tin chung**

| Mã chức năng | DICT-01 |
| :---- | :---- |
| **Tác nhân (Actor)** | Người học (Learner) |
| **Mục tiêu** | Nhận diện chữ Hán từ camera để tra cứu chi tiết 1 từ đơn lẻ HOẶC dịch nguyên câu, phân tích cấu trúc ngữ pháp và luyện nói |
| **Tiền điều kiện (Pre-condition)** | Người dùng đang mở chức năng tra cứu từ điển; thiết bị đã cấp quyền truy cập Camera |
| **Hậu điều kiện (Post-condition)** | Hiển thị kết quả tra từ hoặc dịch câu; lưu lịch sử tra cứu; hỗ trợ lưu trữ và chuyển tiếp các use case học tập liên quan |

### **1.2. Luồng chính (Main Flow)**

| Bước | Tác nhân | Hành động |
| :---- | :---- | :---- |
| **1** | Người dùng | Bấm nút Camera tại ô tìm kiếm. |
| **2** | Hệ thống | Mở màn hình chụp ảnh quét văn bản. |
| **3** | Người dùng | Chụp ảnh vùng văn bản chữ Hán cần tra cứu. |
| **4** | Hệ thống | Nhận diện văn bản trong ảnh và phân loại:• Trường hợp A (Từ đơn): Chuyển đến Bước 5A.• Trường hợp B (Cả câu): Chuyển đến Bước 5B. |
| **5A** | Hệ thống | \[Xử lý 1 từ đơn\] Hiển thị màn hình Chi tiết từ điển gồm:1\. Mặt chữ Hán, 2\. Pinyin, 3\. Audio phát âm, 4\. Phân loại từ, 5\. Nghĩa của từ, 6\. AI giải thích sơ lược, 7\. Cấu tạo từ (Bộ thủ, chiết tự), 8\. Câu ví dụ thực tế, 9\. Thứ tự nét vẽ, 10\. Danh sách từ liên quan. |
| **5B** | Hệ thống | \[Xử lý cả câu\] Hiển thị màn hình Dịch câu & Phân tích cấu trúc gồm:1\. Câu chữ Hán gốc, 2\. Pinyin toàn câu, 3\. Audio phát âm toàn câu, 4\. Bản dịch nghĩa tiếng Việt, 5\. Cấu trúc ngữ pháp được dùng trong câu, 6\. Ví dụ minh họa cấu trúc, 7\. Các cấu trúc ngữ pháp liên quan. |
| **6** | Người dùng | Xem thông tin hoặc thực hiện các thao tác tương tác tiếp theo. |
| **7** | \- | Use case kết thúc. |

### **1.3. Luồng rẽ nhánh & Ngoại lệ (Alternative & Exception Flows)**

| Mã luồng | Bước | Tác nhân | Điều kiện & Hành động xử lý |
| :---- | :---- | :---- | :---- |
| **AF-01** | Tại Bước 5B | Người dùng | Bấm vào từng chữ/từ trong câu: Hệ thống chuyển sang màn hình Chi tiết từ điển của từ đơn lẻ đó (DICT-01 \- Luồng 5A). |
| **AF-02** | Tại Bước 5B | Người dùng | Bấm 'Luyện âm' (Luyện nói câu): Hệ thống chuyển sang use case Luyện phát âm AI (QUIZ-VOICE-TRANS) để thu âm và chấm điểm. |
| **AF-03** | Tại Bước 5A/5B | Người dùng | Bấm 'Lưu câu' / 'Lưu từ': Hệ thống lưu câu hoặc từ vào mục Sổ từ đã lưu. |
| **AF-04** | Tại Bước 5A/5B | Người dùng | Bấm 'Flashcard': Hệ thống tạo thẻ Flashcard tương ứng (thẻ câu hoặc thẻ từ) và lưu vào bộ ôn tập Flashcard (FLASHCARD-01). |
| **AF-05** | Tại Bước 5A | Người dùng | Bấm 'Xem nét vẽ': Hệ thống hiển thị mô tả thứ tự từng nét viết của chữ Hán. |
| **AF-06** | Tại Bước 5A | Người dùng | Bấm 'Tập viết chữ này': Hệ thống chuyển sang use case Luyện viết chữ Hán (QUIZ-WRITE-HANZI). |
| **AF-07** | Tại Bước 5A/5B | Người dùng | Bấm nút 'Quay lại': Hệ thống quay trở lại màn hình tìm kiếm ban đầu (DICT-01). |
| **AF-08** | Tại Bước 2 | Hệ thống | Chưa cấp quyền Camera: Hiển thị thông báo yêu cầu cấp quyền Camera. |
| **AF-09** | Tại Bước 4 | Hệ thống | Không nhận diện được chữ / Ảnh mờ: Hiển thị thông báo 'Không nhận diện được chữ Hán, vui lòng chụp lại'. |
| **AF-10** | Tại Bước 4 | Hệ thống | Lỗi kết nối mạng: Hiển thị thông báo lỗi mạng và cho phép thử lại. |

### **1.4. Ràng buộc dữ liệu (Validation Rules \- VR)**

| Mã VR | Trường dữ liệu | Ràng buộc kiểm tra | Thông báo lỗi hiển thị |
| :---- | :---- | :---- | :---- |
| **VR01** | Vùng ảnh quét | Phải chứa ít nhất 01 ký tự Hán tự hợp lệ | Không tìm thấy ký tự tiếng Trung trong ảnh |
| **VR02** | Tệp hình ảnh | Định dạng ảnh hợp lệ (JPG, PNG), dung lượng \<= 10MB | Hình ảnh không hợp lệ hoặc dung lượng quá lớn |
| **VR03** | Phân định Từ vs. Câu | • Chuỗi \<= 4 ký tự và không có thành phần ngữ pháp: Điều hướng luồng Từ đơn (5A).• Chuỗi \> 4 ký tự hoặc có cấu trúc câu: Điều hướng luồng Dịch câu (5B). | Hệ thống tự động phân loại |

### **1.5. Quy tắc nghiệp vụ (Business Rules \- BR)**

| Mã BR | Tên quy tắc | Nội dung chi tiết |
| :---- | :---- | :---- |
| **BR01** | Tương tác từ trong câu (Interactive Hanzi) | Mỗi ký tự/từ trong câu chữ Hán gốc là một thành phần tương tác độc lập. Khi người dùng bấm vào sẽ điều hướng trực tiếp sang màn hình tra cứu từ điển từ đơn (DICT-01 \- Luồng 5A). |
| **BR02** | Trích xuất Cấu trúc ngữ pháp | Tự động nhận diện cấu trúc ngữ pháp trọng tâm trong câu (câu chữ 把, chữ 被, câu chữ 想, câu so sánh 比...), cung cấp ví dụ mẫu tương ứng và liệt kê các cấu trúc liên quan. |
| **BR03** | Xử lý Phát âm Audio | Phát âm câu phải liền mạch, tuân thủ đúng quy tắc biến điệu thanh điệu trong ngữ cảnh câu (biến điệu hai thanh 3, biến điệu của 一 và 不). |
| **BR04** | Tự động lưu Lịch sử tra cứu | Tự động lưu bản ghi tra cứu (từ hoặc câu) vào danh sách 'Lịch sử tìm kiếm' gần đây (tối đa 50 bản ghi gần nhất). |
| **BR05** | Chống trùng lặp dữ liệu | Khi người dùng bấm Lưu từ, Lưu câu hoặc Thêm vào Flashcard nhiều lần cho cùng 1 mục, hệ thống chỉ cập nhật thời gian thao tác gần nhất, không tạo bản ghi trùng lặp. |
| **BR06** | Khởi tạo thẻ Flashcard | Thẻ tạo mới (từ vựng hoặc mẫu câu) được gán mức ghi nhớ ban đầu \= 0 và nạp vào hàng đợi ôn tập ngắt quãng (SRS). |

