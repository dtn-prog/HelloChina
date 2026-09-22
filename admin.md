# **1\. Chức năng đăng nhập super admin**

## **1.1. Thông tin chung**

| Mục | Nội dung |
| :---- | :---- |
| **Mã chức năng** | ADM-AUTH-01 |
| **Tên chức năng** | Đăng nhập super admin (Super Admin Sign In) |
| **Tác nhân** | Quản trị viên (Admin) |
| **Mục tiêu** | Xác thực danh tính quản trị viên qua Email/SĐT và Mật khẩu để vào hệ thống CMS. |
| **Tiền điều kiện** | Đã có tài khoản Admin trên hệ thống; đang ở màn hình Đăng nhập. |
| **Hậu điều kiện** | Đăng nhập thành công, chuyển hướng vào màn hình Trang chủ (Dashboard). |

 

 

## **1.2. Luồng chính (Main Flow)**

| Bước | Tác nhân | Hành động |
| ----- | :---- | :---- |
| **1** | Quản trị viên | Nhập: Email hoặc Số điện thoại và Mật khẩu. |
| **2** | Quản trị viên | Bấm nút "ĐĂNG NHẬP". |
| **3** | Hệ thống | Kiểm tra hợp lệ dữ liệu (theo VR) và xác thực tài khoản (theo BR). |
| **4** | Hệ thống | Thông báo đăng nhập thành công, chuyển hướng vào Dashboard. |

 

 

## **1.3. Luồng rẽ nhánh & Ngoại lệ (Alternative & Exception Flows)**

| Mã luồng | Điều kiện kích hoạt | Hành động xử lý của Hệ thống |
| :---- | :---- | :---- |
| **AF-01** | Bỏ trống thông tin (Vi phạm VR01, VR02) | Báo lỗi: "Vui lòng nhập đầy đủ thông tin". Dừng use case. |
| **AF-02** | Sai định dạng Email/SĐT (Vi phạm VR01) | Báo lỗi: "Email hoặc Số điện thoại không hợp lệ". Dừng use case. |
| **AF-03** | Sai tài khoản hoặc mật khẩu (Vi phạm BR01) | Báo lỗi: "Thông tin đăng nhập hoặc mật khẩu không chính xác". Dừng use case. |
| **AF-04** | Sai mật khẩu liên tiếp 5 lần (Vi phạm BR03) | Tạm khóa đăng nhập 15 phút. Báo lỗi: "Đã thử sai 5 lần. Vui lòng thử lại sau 15 phút". Dừng use case. |
| **AF-05** | Tài khoản bị khóa hoặc không có quyền Admin | Báo lỗi: "Tài khoản bị khóa hoặc không có quyền truy cập". Dừng use case. |
| **AF-06** | Mất kết nối mạng / Lỗi máy chủ | Báo lỗi: "Không thể kết nối. Vui lòng thử lại sau". Dừng use case. |

 

 

## **1.4. Ràng buộc dữ liệu (Validation Rules \- VR)**

| Mã VR | Trường dữ liệu | Quy tắc kiểm tra | Thông báo lỗi |
| :---- | :---- | :---- | :---- |
| **VR01** | Email / SĐT | • Bắt buộc nhập. • Nhận diện đúng định dạng Email (...@...) hoặc SĐT (10 chữ số). | "Vui lòng nhập đúng định dạng Email hoặc Số điện thoại" |
| **VR02** | Mật khẩu | • Bắt buộc nhập. • Ẩn dạng chấm tròn ••••••, có icon 👁️ xem mật khẩu. | "Vui lòng nhập mật khẩu" |

 

 

## **1.5. Quy tắc nghiệp vụ (Business Rules \- BR)**

| Mã BR | Tên quy tắc | Nội dung chi tiết |
| :---- | :---- | :---- |
| **BR01** | Xác thực mật khẩu | So khớp mật khẩu đã mã hóa (BCrypt/Argon2). |
| **BR02** | Trạng thái & Quyền hạn | Tài khoản phải ở trạng thái Active và có quyền quản trị (is\_admin \= TRUE). |
| **BR03** | Giới hạn lần thử | Sai quá 5 lần liên tiếp: tạm khóa đăng nhập trong 15 phút. Đăng nhập đúng: reset bộ đếm về 0\. |
| **BR04** | Lưu phiên làm việc | Cấp mã JWT token để duy trì phiên làm việc; tự động hết hạn sau 2 giờ không thao tác. |
| **BR05** | Ghi nhận thời gian đăng nhập | Cập nhật mốc thời gian đăng nhập thành công vào trường last\_login\_at trong CSDL để phục vụ hiển thị ở chức năng Quản lý tài khoản (ADM-USER-05). |

 

 

# **2\. Chức năng xem thông tin hồ sơ super admin**

## **2.1. Thông tin chung**

| Mục | Nội dung |
| :---- | :---- |
| **Mã chức năng** | ADM-AUTH-02 |
| **Tên chức năng** | Xem thông tin hồ sơ super admin (View Super Admin Profile) |
| **Tác nhân** | Quản trị viên (Admin) |
| **Mục tiêu** | Hiển thị thông tin cá nhân của tài khoản quản trị viên đang đăng nhập. |
| **Tiền điều kiện** | Quản trị viên đã đăng nhập thành công vào Cổng Quản trị. |
| **Hậu điều kiện** | Thông tin hồ sơ hiển thị đầy đủ trên giao diện. |

 

 

## **2.2. Luồng chính (Main Flow)**

| Bước | Tác nhân | Hành động |
| ----- | :---- | :---- |
| **1** | Quản trị viên | Bấm vào ảnh đại diện (avatar) trên thanh điều hướng (header). |
| **2** | Hệ thống | Lấy dữ liệu hồ sơ của tài khoản đang đăng nhập từ hệ thống. |
| **3** | Hệ thống | Hiển thị thông tin cá nhân: Ảnh đại diện, Họ và tên, Email, Số điện thoại. |
| **4** | \- | Use case kết thúc. |

 

 

## **2.3. Luồng rẽ nhánh & Ngoại lệ (Alternative & Exception Flows)**

| Mã luồng | Điều kiện kích hoạt | Hành động xử lý của Hệ thống |
| :---- | :---- | :---- |
| **AF-01** | Hết hạn phiên đăng nhập (Token hết hạn) | Chuyển hướng về màn hình Đăng nhập. Báo lỗi: "Phiên làm việc đã hết hạn. Vui lòng đăng nhập lại". Dừng use case. |
| **AF-02** | Mất kết nối mạng / Lỗi máy chủ | Báo lỗi: "Không thể tải thông tin hồ sơ. Vui lòng thử lại sau". Dừng use case. |

 

 

## **2.4. Ràng buộc dữ liệu (Validation Rules \- VR)**

| Mã VR | Trường dữ liệu | Quy tắc hiển thị |
| :---- | :---- | :---- |
| **VR01** | Ảnh đại diện (Avatar) | • Hiển thị ảnh của tài khoản. • Nếu chưa có ảnh: hiển thị ảnh mặc định hệ thống. |
| **VR02** | Họ và tên | • Hiển thị đầy đủ họ tên (chỉ đọc \- Read-only). |
| **VR03** | Email | • Hiển thị địa chỉ email đăng ký (chỉ đọc \- Read-only). |
| **VR04** | Số điện thoại | • Hiển thị số điện thoại (chỉ đọc \- Read-only); nếu chưa có hiển thị "Chưa cập nhật". |

 

 

## **2.5. Quy tắc nghiệp vụ (Business Rules \- BR)**

| Mã BR | Tên quy tắc | Nội dung chi tiết |
| :---- | :---- | :---- |
| **BR01** | Bảo mật dữ liệu cá nhân | Chỉ hiển thị đúng thông tin của tài khoản quản trị viên đang trong phiên đăng nhập hiện tại. |
| **BR02** | Chế độ chỉ xem | Màn hình này chỉ có chức năng hiển thị thông tin (Read-only), không cho phép chỉnh sửa trực tiếp. |
| **BR03** | Ảnh đại diện mặc định | Tự động lấy chữ cái đầu tiên của tên hoặc icon mặc định khi tài khoản chưa cài đặt ảnh đại diện. |

 

 

# **3\. Chức năng sửa thông tin cá nhân super admin**

## **3.1. Thông tin chung**

| Mục | Nội dung |
| :---- | :---- |
| **Mã chức năng** | ADM-AUTH-03 |
| **Tên chức năng** | Sửa thông tin cá nhân super admin (Update Super Admin Profile) |
| **Tác nhân** | Quản trị viên (Admin) |
| **Mục tiêu** | Cho phép quản trị viên chỉnh sửa và cập nhật thông tin cá nhân của mình. |
| **Tiền điều kiện** | Quản trị viên đã đăng nhập thành công vào Cổng Quản trị. |
| **Hậu điều kiện** | Thông tin mới được lưu thành công vào cơ sở dữ liệu. |

 

 

## **3.2. Luồng chính (Main Flow)**

| Bước | Tác nhân | Hành động |
| ----- | :---- | :---- |
| **1** | Quản trị viên | Bấm vào ảnh đại diện (avatar) trên thanh điều hướng. |
| **2** | Hệ thống | Hiển thị màn hình chỉnh sửa gồm các trường: Ảnh đại diện, Họ tên, Email, Số điện thoại (chứa sẵn dữ liệu hiện tại). |
| **3** | Quản trị viên | Thay đổi thông tin cần sửa (chọn ảnh mới, sửa tên, email hoặc SĐT). |
| **4** | Quản trị viên | Bấm nút "LƯU". |
| **5** | Hệ thống | Kiểm tra hợp lệ dữ liệu (theo VR) và kiểm tra trùng lặp (theo BR). |
| **6** | Hệ thống | Cập nhật thông tin vào hệ thống, thông báo "Cập nhật thông tin thành công" và hiển thị dữ liệu mới trên header. |

 

 

## **3.3. Luồng rẽ nhánh & Ngoại lệ (Alternative & Exception Flows)**

| Mã luồng | Điều kiện kích hoạt | Hành động xử lý của Hệ thống |
| :---- | :---- | :---- |
| **AF-01** | Bỏ trống thông tin bắt buộc (Vi phạm VR02, VR03, VR04) | Báo lỗi: "Vui lòng nhập đầy đủ thông tin". Dừng use case. |
| **AF-02** | Sai định dạng Email hoặc SĐT (Vi phạm VR03, VR04) | Báo lỗi: "Email hoặc Số điện thoại không hợp lệ". Dừng use case. |
| **AF-03** | Tải ảnh quá dung lượng hoặc sai định dạng (Vi phạm VR01) | Báo lỗi: "Ảnh không đúng định dạng (chỉ nhận JPG, PNG ≤ 5MB)". Dừng use case. |
| **AF-04** | Email hoặc SĐT mới bị trùng với tài khoản khác (Vi phạm BR01) | Báo lỗi: "Email hoặc Số điện thoại đã tồn tại trên hệ thống". Dừng use case. |
| **AF-05** | Mất kết nối mạng / Lỗi máy chủ | Báo lỗi: "Không thể lưu thông tin. Vui lòng thử lại sau". Dừng use case. |

 

 

## **3.4. Ràng buộc dữ liệu (Validation Rules \- VR)**

| Mã VR | Trường dữ liệu | Quy tắc kiểm tra | Thông báo lỗi |
| :---- | :---- | :---- | :---- |
| **VR01** | Ảnh đại diện | • Định dạng cho phép: .jpg, .jpeg, .png, .webp. • Dung lượng tối đa: 5MB. | "Ảnh đại diện phải là tệp JPG/PNG và nhỏ hơn 5MB" |
| **VR02** | Họ và tên | • Bắt buộc nhập. • Độ dài: 2 – 50 ký tự, không chứa ký tự đặc biệt nguy hiểm. | "Vui lòng nhập họ và tên hợp lệ" |
| **VR03** | Email | • Bắt buộc nhập. • Đúng định dạng email chuẩn (...@...). | "Vui lòng nhập đúng định dạng Email" |
| **VR04** | Số điện thoại | • Bắt buộc nhập. • Chuẩn số điện thoại (10 chữ số). | "Vui lòng nhập đúng số điện thoại (10 chữ số)" |

 

 

## **3.5. Quy tắc nghiệp vụ (Business Rules \- BR)**

| Mã BR | Tên quy tắc | Nội dung chi tiết |
| :---- | :---- | :---- |
| **BR01** | Kiểm tra trùng lặp | Email và Số điện thoại mới không được trùng với bất kỳ tài khoản nào khác trong hệ thống. |
| **BR02** | Cập nhật giao diện tức thì | Sau khi lưu thành công, ảnh đại diện và tên mới được cập nhật ngay lập tức trên thanh header của Cổng Quản trị. |
| **BR03** | Tải ảnh lên máy chủ CDN | Tệp ảnh đại diện mới được nén tự động và lưu trên máy chủ lưu trữ (CDN/Storage), đường dẫn ảnh được cập nhật vào bảng dữ liệu quản trị. |

 

 

# **4\. Chức năng thay đổi mật khẩu super admin**

## **4.1. Thông tin chung**

| Mục | Nội dung |
| :---- | :---- |
| **Mã chức năng** | ADM-AUTH-04 |
| **Tên chức năng** | Thay đổi mật khẩu super admin (Change Super Admin Password) |
| **Tác nhân** | Quản trị viên (Admin) |
| **Mục tiêu** | Cho phép quản trị viên tự thay đổi mật khẩu tài khoản của mình để bảo mật. |
| **Tiền điều kiện** | Quản trị viên đã đăng nhập thành công vào Cổng Quản trị. |
| **Hậu điều kiện** | Mật khẩu mới được cập nhật thành công vào hệ thống. |

 

 

## **4.2. Luồng chính (Main Flow)**

| Bước | Tác nhân | Hành động |
| ----- | :---- | :---- |
| **1** | Quản trị viên | Bấm nút tài khoản Admin trên thanh điều hướng. |
| **2** | Hệ thống | Hiển thị form Đổi mật khẩu gồm 3 ô: Mật khẩu hiện tại, Mật khẩu mới, Xác nhận mật khẩu mới. |
| **3** | Quản trị viên | Nhập đầy đủ thông tin vào 3 ô nhập liệu. |
| **4** | Quản trị viên | Bấm nút "LƯU". |
| **5** | Hệ thống | Kiểm tra hợp lệ dữ liệu (theo VR) và xác thực mật khẩu hiện tại (theo BR). |
| **6** | Hệ thống | Cập nhật mật khẩu mới đã mã hóa vào hệ thống, thông báo: "Đổi mật khẩu thành công". |

 

 

## **4.3. Luồng rẽ nhánh & Ngoại lệ (Alternative & Exception Flows)**

| Mã luồng | Điều kiện kích hoạt | Hành động xử lý của Hệ thống |
| :---- | :---- | :---- |
| **AF-01** | Bỏ trống bất kỳ ô nào trong 3 ô (Vi phạm VR01, VR02, VR03) | Báo lỗi: "Vui lòng nhập đầy đủ thông tin". Dừng use case. |
| **AF-02** | Nhập sai Mật khẩu hiện tại (Vi phạm BR01) | Báo lỗi: "Mật khẩu hiện tại không chính xác". Dừng use case. |
| **AF-03** | Mật khẩu mới dưới 8 ký tự (Vi phạm VR02) | Báo lỗi: "Mật khẩu mới phải có tối thiểu 8 ký tự". Dừng use case. |
| **AF-04** | Xác nhận mật khẩu không khớp với Mật khẩu mới (Vi phạm VR03) | Báo lỗi: "Mật khẩu xác nhận không khớp". Dừng use case. |
| **AF-05** | Mật khẩu mới trùng với Mật khẩu hiện tại (Vi phạm BR03) | Báo lỗi: "Mật khẩu mới không được trùng với mật khẩu hiện tại". Dừng use case. |
| **AF-06** | Mất kết nối mạng / Lỗi máy chủ | Báo lỗi: "Không thể đổi mật khẩu. Vui lòng thử lại sau". Dừng use case. |

 

 

## **4.4. Ràng buộc dữ liệu (Validation Rules \- VR)**

| Mã VR | Trường dữ liệu | Quy tắc kiểm tra | Thông báo lỗi |
| :---- | :---- | :---- | :---- |
| **VR01** | Mật khẩu hiện tại | • Bắt buộc nhập. • Mặc định ẩn dạng ••••••, có icon 👁️ xem mật khẩu. | "Vui lòng nhập mật khẩu hiện tại" |
| **VR02** | Mật khẩu mới | • Bắt buộc nhập. • Độ dài tối thiểu 8 ký tự. • Mặc định ẩn dạng ••••••, có icon 👁️ xem mật khẩu. | "Mật khẩu mới phải có tối thiểu 8 ký tự" |
| **VR03** | Xác nhận mật khẩu mới | • Bắt buộc nhập. • Khớp chính xác 100% với Mật khẩu mới. • Mặc định ẩn dạng ••••••, có icon 👁️ xem mật khẩu. | "Mật khẩu xác nhận không khớp" |

 

 

## **4.5. Quy tắc nghiệp vụ (Business Rules \- BR)**

| Mã BR | Tên quy tắc | Nội dung chi tiết |
| :---- | :---- | :---- |
| **BR01** | Xác thực mật khẩu cũ | So khớp mật khẩu hiện tại quản trị viên nhập với mật khẩu mã hóa đang lưu trong cơ sở dữ liệu. |
| **BR02** | Mã hóa mật khẩu mới | Mật khẩu mới bắt buộc phải được mã hóa bằng thuật toán an toàn (BCrypt/Argon2) trước khi lưu DB. |
| **BR03** | Chống trùng mật khẩu cũ | Mật khẩu mới không được trùng với mật khẩu hiện tại đang sử dụng. |
| **BR04** | Ghi nhật ký bảo mật | Ghi nhận sự kiện đổi mật khẩu vào hệ thống kiểm toán (Audit Log) kèm mốc thời gian và địa chỉ IP. |

 

 

# **5\. Chức năng xem danh sách tài khoản admin**

## **5.1. Thông tin chung**

| Mục | Nội dung |
| :---- | :---- |
| **Mã chức năng** | ADM-USER-05 |
| **Tên chức năng** | Xem danh sách tài khoản admin (View Admin Staff List) |
| **Tác nhân** | Quản trị viên (Admin) |
| **Mục tiêu** | Hiển thị danh sách toàn bộ tài khoản quản trị nội bộ trong hệ thống để theo dõi, quản lý. |
| **Tiền điều kiện** | Quản trị viên đã đăng nhập thành công vào Cổng Quản trị. |
| **Hậu điều kiện** | Danh sách tài khoản admin hiển thị đầy đủ trên màn hình. |

 

 

## **5.2. Luồng chính (Main Flow)**

| Bước | Tác nhân | Hành động |
| ----- | :---- | :---- |
| **1** | Quản trị viên | Bấm vào mục Admin của mục quản trị tài khoản. |
| **2** | Hệ thống | Lấy dữ liệu danh sách tài khoản admin từ cơ sở dữ liệu. |
| **3** | Hệ thống | Hiển thị bảng danh sách admin gồm các thông tin: **Ảnh đại diện (avatar), Họ tên, Email, Số điện thoại, Mã admin, Đăng nhập (Thời gian đăng nhập gần nhất), Trạng thái (Khóa / Hoạt động)**. |
| **4** | Quản trị viên | Xem thông tin danh sách; use case kết thúc. |

 

 

## **5.3. Luồng rẽ nhánh & Ngoại lệ (Alternative & Exception Flows)**

| Mã luồng | Điều kiện kích hoạt | Hành động xử lý của Hệ thống |
| :---- | :---- | :---- |
| **AF-01** | Chưa có dữ liệu tài khoản admin nào | Hiển thị thông báo: \*"Không có dữ liệu tài khoản admin"\*. |
| **AF-02** | Mất kết nối mạng / Lỗi máy chủ | Báo lỗi: \*"Không thể tải danh sách tài khoản admin. Vui lòng thử lại sau"\*. Dừng use case. |

 

 

## **5.4. Ràng buộc dữ liệu (Validation Rules \- VR)**

| Mã VR | Trường dữ liệu | Quy tắc hiển thị |
| :---- | :---- | :---- |
| **VR01** | Ảnh đại diện (Avatar) | Hiển thị ảnh cá nhân của admin; nếu chưa có hiển thị ảnh mặc định. |
| **VR02** | Mã admin | Mã định danh duy nhất của admin (Read-only). |
| **VR03** | Họ tên, Email, SĐT | Hiển thị dạng chữ chỉ đọc (Read-only). |
| **VR04** | Đăng nhập | • Hiển thị mốc thời gian đăng nhập thành công gần nhất định dạng: DD/MM/YYYY HH:mm:ss. • Nếu tài khoản chưa từng đăng nhập lần nào, hiển thị: Chưa đăng nhập. |
| **VR05** | Trạng thái | Hiển thị 1 trong 2 trạng thái: **Hoạt động** (nhãn màu xanh) hoặc **Khóa** (nhãn màu đỏ/xám). |

 

 

## **5.5. Quy tắc nghiệp vụ (Business Rules \- BR)**

| Mã BR | Tên quy tắc | Nội dung chi tiết |
| :---- | :---- | :---- |
| **BR01** | **Phạm vi hiển thị** | Chỉ lọc và hiển thị danh sách người dùng nội bộ có quyền quản trị (is\_admin \= TRUE). |
| **BR02** | **Nguồn dữ liệu đăng nhập gần nhất** | Cột "Đăng nhập" lấy giá trị từ trường last\_login\_at trong CSDL, được cập nhật tự động mỗi khi tài khoản đăng nhập thành công. |
| **BR03** | **Phân trang dữ liệu** | Hỗ trợ phân trang danh sách (mặc định 10 đến 20 tài khoản/trang) để tối ưu tốc độ tải trang. |

 

 

# **6\. Chức năng xem thông tin chi tiết tài khoản admin**

## **6.1. Thông tin chung**

| Mục | Nội dung |
| :---- | :---- |
| **Mã chức năng** | ADM-USER-06 |
| **Tên chức năng** | Xem thông tin chi tiết tài khoản admin (View Admin Account Details) |
| **Tác nhân** | Quản trị viên (Super Admin, Admin) |
| **Mục tiêu** | Tra cứu hồ sơ thông tin tài khoản và theo dõi lịch sử nhật ký hoạt động của quản trị viên trong hệ thống. |
| **Tiền điều kiện** | Đang ở màn hình Danh sách tài khoản admin (ADM-USER-05). |
| **Hậu điều kiện** | Màn hình hiển thị đầy đủ thông tin chi tiết và bảng nhật ký hoạt động của admin được chọn. |

 

 

## **6.2. Luồng chính (Main Flow)**

| Bước | Tác nhân | Hành động |
| ----- | :---- | :---- |
| **1** | Quản trị viên | Bấm chọn 1 tài khoản admin từ danh sách. |
| **2** | Hệ thống | Truy xuất dữ liệu hồ sơ và lịch sử hoạt động của admin từ CSDL. |
| **3** | Hệ thống | Hiển thị 2 nhóm thông tin: • **Nhóm 1**: Thông tin tài khoản admin (kèm bảng Thiết bị đã ghi nhớ). • **Nhóm 2**: Bảng nhật ký hoạt động. |
| **4** | Quản trị viên | Xem thông tin chi tiết; use case kết thúc. |

 

 

## **6.3. Luồng rẽ nhánh & Ngoại lệ (Alternative & Exception Flows)**

| Mã luồng | Điều kiện kích hoạt | Xử lý của Hệ thống |
| :---- | :---- | :---- |
| **AF-01** | Tài khoản admin không tồn tại / đã bị xóa | Báo lỗi: \*"Không tìm thấy thông tin tài khoản admin"\*. Quay lại danh sách. |
| **AF-02** | Chưa có thiết bị ghi nhớ hoặc chưa có nhật ký | Hiển thị: \*"Chưa có dữ liệu"\*. |
| **AF-03** | Mất kết nối mạng / Lỗi máy chủ | Báo lỗi: \*"Không thể tải dữ liệu. Vui lòng thử lại sau"\*. Dừng use case. |

 

 

## **6.4. Ràng buộc dữ liệu (Validation Rules \- VR)**

### **Nhóm 1 — Thông tin tài khoản admin**

| Mã VR | Trường dữ liệu | Quy tắc hiển thị |
| :---- | :---- | :---- |
| **VR01** | Ảnh đại diện (Avatar) | Hiển thị ảnh đại diện của admin; nếu chưa có hiển thị ảnh mặc định. |
| **VR02** | Mã admin | Mã định danh duy nhất (Read-only, ví dụ: ADM00001). |
| **VR03** | Họ và tên | Hiển thị họ tên đầy đủ. |
| **VR04** | Email / Số điện thoại | Thông tin liên hệ công việc (trường trống hiển thị: Chưa cập nhật). |
| **VR05** | Vai trò (Role) | Hiển thị: **Super Admin** hoặc **Admin**. |
| **VR06** | Trạng thái | Hiển thị 1 trong 2 trạng thái: **Hoạt động** (xanh) hoặc **Khóa** (đỏ). |
| **VR07** | Ngày tạo / Cập nhật | Định dạng hiển thị: DD/MM/YYYY HH:mm:ss. |
| **VR08** | Đăng nhập gần nhất | Mốc thời gian DD/MM/YYYY HH:mm:ss (chưa từng đăng nhập: Chưa đăng nhập). |
| **VR09** | Thiết bị đã ghi nhớ | Bảng con: **Tên thiết bị, Nền tảng (iOS/Android/Web), Hoạt động gần nhất**. |

 

### **Nhóm 2 — Nhật ký hoạt động (Bảng 5 cột giống học viên)**

| Mã VR | Cột hiển thị | Quy tắc hiển thị (Bảng 5 cột) |
| :---- | :---- | :---- |
| **VR10** | **Thời gian** | Định dạng DD/MM/YYYY HH:mm:ss. |
| **VR11** | **Loại hoạt động** | Tên hành vi (Đăng nhập, Đổi mật khẩu, Thêm tài khoản, Khóa/Mở khóa, Xóa tài khoản...). |
| **VR12** | **Nội dung chi tiết** | Mô tả chi tiết hành động hoặc đối tượng bị tác động. |
| **VR13** | **Địa chỉ IP** | Địa chỉ IP của thiết bị thực hiện (IPv4 hoặc IPv6). |
| **VR14** | **Trạng thái** | Hiển thị kết quả: **Thành công** hoặc **Thất bại**. |

 

 

## **6.5. Quy tắc nghiệp vụ (Business Rules \- BR)**

| Mã BR | Tên quy tắc | Nội dung chi tiết |
| :---- | :---- | :---- |
| **BR01** | **Chế độ chỉ xem** | Màn hình thuần túy chỉ đọc (Read-only), chỉ phục vụ tra cứu thông tin, không cho phép chỉnh sửa dữ liệu. |
| **BR02** | **Truy xuất theo tài khoản được chọn** | Dữ liệu thông tin và nhật ký được tải chính xác theo admin\_id của tài khoản admin được bấm chọn từ danh sách. |
| **BR03** | **Sắp xếp & Phân trang** | Danh sách thiết bị và nhật ký sắp xếp theo thời gian mới nhất lên đầu (created\_at DESC); hỗ trợ phân trang cho bảng nhật ký (mặc định 10 – 20 bản ghi/trang). |

 

 

# **7\. Chức năng thay đổi thông tin tài khoản admin**

## **7.1. Thông tin chung**

| Mục | Nội dung |
| :---- | :---- |
| **Mã chức năng** | ADM-USER-07 |
| **Tên chức năng** | Thay đổi thông tin tài khoản admin (Edit Admin Staff) |
| **Tác nhân** | Quản trị viên (Admin) |
| **Mục tiêu** | Cho phép cập nhật thông tin cá nhân, vai trò và trạng thái hoạt động của một tài khoản admin. |
| **Tiền điều kiện** | Đang ở màn hình Danh sách tài khoản admin (ADM-USER-05) hoặc Xem chi tiết tài khoản admin (ADM-USER-06). |
| **Hậu điều kiện** | Thông tin tài khoản admin được cập nhật thành công vào hệ thống. |

 

 

## **7.2. Luồng chính (Main Flow)**

| Bước | Tác nhân | Hành động |
| ----- | :---- | :---- |
| **1** | Quản trị viên | Bấm chọn 1 tài khoản admin từ danh sách. |
| **2** | Hệ thống | Hiển thị form chỉnh sửa gồm thông tin cũ: **Ảnh đại diện, Họ tên, Email, Số điện thoại, Mã admin, Trạng thái, Vai trò**. |
| **3** | Quản trị viên | Thay đổi thông tin cần cập nhật (chọn ảnh mới, sửa tên, email, SĐT, vai trò; chọn trạng thái: Hoạt động hoặc Khóa). |
| **4** | Quản trị viên | Bấm nút **"LƯU"**. |
| **5** | Hệ thống | Kiểm tra hợp lệ dữ liệu (theo VR) và kiểm tra tính duy nhất (theo BR). |
| **6** | Hệ thống | Cập nhật thông tin vào hệ thống, thông báo: \*"Cập nhật thông tin tài khoản admin thành công"\*. |

 

 

## **7.3. Luồng rẽ nhánh & Ngoại lệ (Alternative & Exception Flows)**

| Mã luồng | Điều kiện kích hoạt | Hành động xử lý của Hệ thống |
| :---- | :---- | :---- |
| **AF-01** | Bỏ trống thông tin bắt buộc (Vi phạm VR02, VR03, VR04) | Báo lỗi: \*"Vui lòng nhập đầy đủ thông tin bắt buộc"\*. Dừng use case. |
| **AF-02** | Sai định dạng Email hoặc SĐT (Vi phạm VR03, VR04) | Báo lỗi: \*"Email hoặc Số điện thoại không hợp lệ"\*. Dừng use case. |
| **AF-03** | Email hoặc SĐT bị trùng với tài khoản khác (Vi phạm BR02) | Báo lỗi: \*"Email hoặc Số điện thoại đã tồn tại trên hệ thống"\*. Dừng use case. |
| **AF-04** | Tự khóa tài khoản của chính mình (Vi phạm BR03) | Báo lỗi: \*"Không thể tự khóa tài khoản quản trị đang đăng nhập"\*. Dừng use case. |
| **AF-05** | Mất kết nối mạng / Lỗi máy chủ | Báo lỗi: \*"Không thể lưu thông tin. Vui lòng thử lại sau"\*. Dừng use case. |

 

 

## **7.4. Ràng buộc dữ liệu (Validation Rules \- VR)**

| Mã VR | Trường dữ liệu | Quy tắc kiểm tra | Thông báo lỗi |
| :---- | :---- | :---- | ----- |
| **VR01** | Mã admin | Không cho phép chỉnh sửa (Read-only / Disabled). | \- |
| **VR02** | Trạng thái | • Bắt buộc chọn. • Cho phép chọn 1 trong 2 giá trị trong danh sách: **Hoạt động** hoặc **Khóa**. | \*"Vui lòng chọn trạng thái tài khoản"\* |
| **VR03** | Họ và tên | • Bắt buộc nhập. • Độ dài 2 – 50 ký tự. | \*"Vui lòng nhập họ và tên hợp lệ"\* |
| **VR04** | Email / SĐT | • Bắt buộc nhập. • Đúng định dạng email chuẩn và số điện thoại 10 chữ số. | \*"Email hoặc Số điện thoại không đúng định dạng"\* |

 

 

## **7.5. Quy tắc nghiệp vụ (Business Rules \- BR)**

| Mã BR | Tên quy tắc | Nội dung chi tiết |
| :---- | :---- | :---- |
| **BR01** | **Hiệu lực khóa tài khoản** | Khi chuyển trạng thái tài khoản sang Khóa, tài khoản admin đó bị vô hiệu hóa ngay lập tức và tự động đăng xuất trên mọi thiết bị. |
| **BR02** | **Kiểm tra trùng lặp** | Email và Số điện thoại mới không được trùng với bất kỳ tài khoản nào khác trong cơ sở dữ liệu. |
| **BR03** | **Bảo vệ tài khoản hiện tại** | Quản trị viên không được phép tự chuyển trạng thái tài khoản của chính mình sang Khóa để tránh mất quyền quản trị hệ thống. |

 

 

# **8\. Chức năng thay đổi mật khẩu tài khoản admin và học viên**

## **8.1. Thông tin chung**

| Mục | Nội dung |
| :---- | :---- |
| **Mã chức năng** | ADM-USER-08 |
| **Tên chức năng** | Thay đổi mật khẩu tài khoản admin và học viên (Reset Admin & Learner Password) |
| **Tác nhân** | Quản trị viên (Super Admin, Admin) |
| **Mục tiêu** | Cho phép quản trị viên đặt lại mật khẩu mới cho tài khoản admin hoặc tài khoản học viên khi có yêu cầu cấp lại / hỗ trợ xử lý sự cố. |
| **Tiền điều kiện** | Đang ở màn hình Danh sách tài khoản admin hoặc Danh sách / Chi tiết tài khoản học viên. |
| **Hậu điều kiện** | Mật khẩu mới của tài khoản được cập nhật và mã hóa thành công trong cơ sở dữ liệu. |

 

 

## **8.2. Luồng chính (Main Flow)**

| Bước | Tác nhân | Hành động |
| ----- | :---- | :---- |
| **1** | Quản trị viên | Bấm nút **"Đổi mật khẩu"** tại dòng tài khoản trong danh sách (hoặc trên giao diện chi tiết). |
| **2** | Hệ thống | Kiểm tra phân quyền thao tác (theo BR01). Hiển thị popup nhập mật khẩu mới gồm 2 ô: **Mật khẩu mới** và **Xác nhận mật khẩu mới**. |
| **3** | Quản trị viên | Nhập Mật khẩu mới và Xác nhận mật khẩu mới. |
| **4** | Quản trị viên | Bấm nút **"LƯU"**. |
| **5** | Hệ thống | Kiểm tra hợp lệ dữ liệu (theo VR) và so khớp 2 ô mật khẩu. |
| **6** | Hệ thống | Mã hóa mật khẩu (theo BR03), cập nhật vào CSDL, thu hồi phiên làm việc cũ (theo BR04) và thông báo: \*"Đổi mật khẩu thành công"\*; use case kết thúc. |

 

 

## **8.3. Luồng rẽ nhánh & Ngoại lệ (Alternative & Exception Flows)**

| Mã luồng | Điều kiện kích hoạt | Hành động xử lý của Hệ thống |
| :---- | :---- | :---- |
| **AF-01** | Bỏ trống 1 trong 2 ô mật khẩu (Vi phạm VR01, VR02) | Báo lỗi: \*"Vui lòng nhập đầy đủ thông tin"\*. Dừng use case. |
| **AF-02** | Mật khẩu mới dưới 8 ký tự (Vi phạm VR01) | Báo lỗi: \*"Mật khẩu mới phải có tối thiểu 8 ký tự"\*. Dừng use case. |
| **AF-03** | Xác nhận mật khẩu không khớp (Vi phạm VR02) | Báo lỗi: \*"Mật khẩu xác nhận không khớp"\*. Dừng use case. |
| **AF-04** | Admin thường đổi mật khẩu tài khoản Admin khác (Vi phạm BR01) | Báo lỗi: \*"Chỉ Super Admin mới có quyền đổi mật khẩu cho tài khoản quản trị khác"\*. Dừng use case. |
| **AF-05** | Mất kết nối mạng / Lỗi máy chủ | Báo lỗi: \*"Không thể đổi mật khẩu. Vui lòng thử lại sau"\*. Dừng use case. |

 

 

## **8.4. Ràng buộc dữ liệu (Validation Rules \- VR)**

| Mã VR | Trường dữ liệu | Quy tắc kiểm tra | Thông báo lỗi |
| :---- | :---- | :---- | :---- |
| **VR01** | Mật khẩu mới | • Bắt buộc nhập. • Độ dài tối thiểu 8 ký tự. • Mặc định ẩn dạng ••••••, có icon 👁️ xem mật khẩu. | \*"Mật khẩu mới phải có tối thiểu 8 ký tự"\* |
| **VR02** | Xác nhận mật khẩu mới | • Bắt buộc nhập. • Khớp chính xác 100% với Mật khẩu mới. • Mặc định ẩn dạng ••••••, có icon 👁️ xem mật khẩu. | \*"Mật khẩu xác nhận không khớp"\* |

 

 

## **8.5. Quy tắc nghiệp vụ (Business Rules \- BR)**

| Mã BR | Tên quy tắc | Nội dung chi tiết |
| :---- | :---- | :---- |
| **BR01** | **Phân quyền đổi mật khẩu theo vai trò** | • **Đổi mật khẩu Admin**: Chỉ tài khoản có vai trò **Super Admin** mới có quyền đổi mật khẩu cho tài khoản admin khác. Admin thường không có quyền này. • **Đổi mật khẩu Học viên**: Cả **Admin** và **Super Admin** đều có quyền đổi mật khẩu cho tài khoản học viên. |
| **BR02** | **Không yêu cầu mật khẩu cũ** | Khi quản trị viên cấp quản lý đặt lại mật khẩu cho tài khoản người dùng khác, hệ thống không yêu cầu cung cấp mật khẩu cũ của tài khoản đó. |
| **BR03** | **Mã hóa an toàn** | Mật khẩu mới bắt buộc phải được mã hóa bằng thuật toán an toàn (Argon2id/BCrypt) trước khi lưu vào cơ sở dữ liệu. |
| **BR04** | **Thu hồi phiên làm việc cũ** | Sau khi mật khẩu được đổi thành công, toàn bộ phiên làm việc (Access Token / Refresh Token) của tài khoản đó trên mọi thiết bị lập tức bị vô hiệu hóa, bắt buộc người dùng đăng nhập lại bằng mật khẩu mới. |

 

 

# **9\. Chức năng lọc tài khoản admin và học viên**

## **9.1. Thông tin chung**

| Mục | Nội dung |
| :---- | :---- |
| **Mã chức năng** | ADM-USER-09 |
| **Tên chức năng** | Lọc tài khoản admin và học viên (Filter Admin & Learner Accounts) |
| **Tác nhân** | Quản trị viên (Super Admin, Admin) |
| **Mục tiêu** | Tìm kiếm và lọc danh sách tài khoản admin hoặc học viên theo từ khóa, gói cước hoặc trạng thái để tra cứu nhanh. |
| **Tiền điều kiện** | Đang ở màn hình Danh sách tài khoản admin hoặc Danh sách tài khoản học viên. |
| **Hậu điều kiện** | Danh sách tài khoản thỏa mãn điều kiện lọc hiển thị trên màn hình. |

 

 

## **9.2. Luồng chính (Main Flow)**

| Bước | Tác nhân | Hành động |
| ----- | :---- | :---- |
| **1** | Quản trị viên | Truy cập màn hình danh sách tài khoản admin hoặc học viên. |
| **2** | Quản trị viên | Nhập từ khóa tìm kiếm (SĐT / Email / Mã số / Họ tên / Tên đăng nhập) và/hoặc chọn bộ lọc (Trạng thái, Gói cước đối với học viên). |
| **3** | Quản trị viên | Bấm nút **"LỌC"**. |
| **4** | Hệ thống | Thực hiện truy vấn và lọc dữ liệu trong cơ sở dữ liệu theo các điều kiện đã chọn. |
| **5** | Hệ thống | Hiển thị danh sách các tài khoản thỏa mãn điều kiện lọc. |
| **6** | Quản trị viên | Xem kết quả lọc; use case kết thúc. |

 

 

## **9.3. Luồng rẽ nhánh & Ngoại lệ (Alternative & Exception Flows)**

| Mã luồng | Điều kiện kích hoạt | Hành động xử lý của Hệ thống |
| :---- | :---- | :---- |
| **AF-01** | Không tìm thấy kết quả phù hợp | Hiển thị bảng trống kèm thông báo: \*"Không tìm thấy tài khoản phù hợp"\*. |
| **AF-02** | Để trống toàn bộ ô tìm kiếm và đặt các bộ lọc về "Tất cả" | Hệ thống tải lại toàn bộ danh sách tài khoản ban đầu. |
| **AF-03** | Mất kết nối mạng / Lỗi máy chủ | Báo lỗi: \*"Không thể lọc dữ liệu. Vui lòng thử lại sau"\*. Dừng use case. |

 

 

## **9.4. Ràng buộc dữ liệu (Validation Rules \- VR)**

| Mã VR | Trường dữ liệu | Quy tắc kiểm tra |
| :---- | :---- | :---- |
| **VR01** | Ô nhập từ khóa | • Không bắt buộc nhập (Tùy chọn). • Tự động cắt khoảng trắng đầu/cuối. • Tìm kiếm gần đúng theo SĐT, Email, Mã định danh (Mã admin / Mã học viên), Họ tên hoặc Tên đăng nhập. |
| **VR02** | Bộ lọc trạng thái | • Bắt buộc chọn 1 giá trị: **Tất cả**, **Hoạt động**, **Khóa** (mặc định: Tất cả). |
| **VR03** | Bộ lọc gói cước (Dành cho học viên) | • Bắt buộc chọn 1 giá trị: **Tất cả**, **Free**, **Premium** (mặc định: Tất cả). |

 

 

## **9.5. Quy tắc nghiệp vụ (Business Rules \- BR)**

| Mã BR | Tên quy tắc | Nội dung chi tiết |
| :---- | :---- | :---- |
| **BR01** | **Cơ chế tìm kiếm đa trường** | Từ khóa nhập vào được so khớp không phân biệt chữ hoa/thường trên các trường: Mã định danh, Họ tên, Tên đăng nhập, Email và Số điện thoại. |
| **BR02** | **Kết hợp điều kiện (Toán tử AND)** | Khi kết hợp nhiều điều kiện (từ khóa, trạng thái, gói cước), hệ thống áp dụng toán tử AND để trả về kết quả thỏa mãn tất cả tiêu chí. |
| **BR03** | **Hiển thị số lượng kết quả & Phân trang** | Hiển thị tổng số tài khoản tìm thấy (Ví dụ: \*"Tìm thấy 10 tài khoản"\*) và hỗ trợ phân trang cho danh sách kết quả. |

 

 

# **10\. Chức năng xóa tài khoản admin và học viên**

## **10.1. Thông tin chung**

| Mục | Nội dung |
| :---- | :---- |
| **Mã chức năng** | ADM-USER-10 |
| **Tên chức năng** | Xóa tài khoản admin và học viên (Delete Admin & Learner Accounts) |
| **Tác nhân** | Quản trị viên (Super Admin, Admin) |
| **Mục tiêu** | Xóa mềm tài khoản admin hoặc tài khoản học viên khỏi hệ thống để ngừng cấp quyền hoặc ngừng hoạt động. |
| **Tiền điều kiện** | Đang ở màn hình Danh sách tài khoản admin hoặc Danh sách tài khoản học viên. |
| **Hậu điều kiện** | Tài khoản bị chuyển trạng thái xóa mềm và thu hồi quyền truy cập. |

 

 

## **10.2. Luồng chính (Main Flow)**

| Bước | Tác nhân | Hành động |
| ----- | :---- | :---- |
| **1** | Quản trị viên | Tích chọn 1 hoặc nhiều tài khoản muốn xóa trong danh sách. |
| **2** | Quản trị viên | Bấm nút **"XÓA"**. |
| **3** | Hệ thống | Hiển thị popup xác nhận: \*"Bạn có chắc chắn muốn xóa (các) tài khoản đã chọn?"\* |
| **4** | Quản trị viên | Bấm nút **"XÁC NHẬN"**. |
| **5** | Hệ thống | Kiểm tra phân quyền thực hiện xóa theo BR01. |
| **6** | Hệ thống | Thực hiện xóa mềm các tài khoản đã chọn trong CSDL và thu hồi quyền truy cập / phiên đăng nhập. |
| **7** | Hệ thống | Thông báo: \*"Xóa tài khoản thành công"\*, tải lại danh sách; use case kết thúc. |

 

 

## **10.3. Luồng rẽ nhánh & Ngoại lệ (Alternative & Exception Flows)**

| Mã luồng | Điều kiện kích hoạt | Hành động xử lý của Hệ thống |
| :---- | :---- | :---- |
| **AF-01** | Tích chọn tất cả trong trang để xóa hàng loạt | Tích chọn toàn bộ tài khoản trong trang hiện tại → Bấm "XÓA" → Xác nhận xóa → Hệ thống xóa mềm toàn bộ danh sách hợp lệ đã chọn. |
| **AF-02** | Bấm "HỦY" tại popup xác nhận | Đóng popup, giữ nguyên trạng thái danh sách. Dừng use case. |
| **AF-03** | Chọn tài khoản của chính bản thân đang đăng nhập (Vi phạm BR02) | Báo lỗi: \*"Không thể tự xóa tài khoản của chính mình"\*. Dừng use case. |
| **AF-04** | Admin thường xóa tài khoản admin khác (Vi phạm BR01) | Báo lỗi: \*"Chỉ Super Admin mới có quyền xóa tài khoản admin"\*. Dừng use case. |
| **AF-05** | Mất kết nối mạng / Lỗi máy chủ | Báo lỗi: \*"Không thể xóa. Vui lòng thử lại sau"\*. Dừng use case. |

 

 

## **10.4. Ràng buộc dữ liệu (Validation Rules \- VR)**

| Mã VR | Thành phần | Quy tắc kiểm tra |
| :---- | :---- | :---- |
| **VR01** | Checkbox chọn dòng | Tích chọn từng tài khoản. Riêng dòng tài khoản của chính Quản trị viên đang thao tác sẽ bị ẩn/khóa checkbox. |
| **VR02** | Checkbox chọn tất cả | Tích chọn: chọn toàn bộ tài khoản trên trang hiện tại (ngoại trừ tài khoản của chính mình nếu là danh sách admin). |
| **VR03** | Nút "XÓA" | • Disabled: khi chưa tích chọn tài khoản nào. • Enabled: khi có ít nhất 1 tài khoản được chọn. |
| **VR04** | Popup xác nhận | Bắt buộc hiển thị cảnh báo xác nhận trước khi thực hiện xóa. |

 

 

## **10.5. Quy tắc nghiệp vụ (Business Rules \- BR)**

| Mã BR | Tên quy tắc | Nội dung chi tiết |
| :---- | :---- | :---- |
| **BR01** | **Phân quyền xóa theo vai trò** | • **Xóa tài khoản admin**: Chỉ tài khoản có vai trò **Super Admin** mới có quyền xóa tài khoản admin khác. Admin thường không được phép xóa. • **Xóa tài khoản học viên**: Cả **Admin** và **Super Admin** đều có quyền xóa tài khoản học viên. |
| **BR02** | **Chống tự xóa tài khoản** | Quản trị viên tuyệt đối không được phép tự xóa tài khoản của chính bản thân mình đang đăng nhập. |
| **BR03** | **Cơ chế xóa mềm (Soft Delete)** | Thực hiện cập nhật cờ is\_deleted \= TRUE và deleted\_at \= Thời điểm xóa. Tuyệt đối không xóa vật lý (Hard Delete) để bảo toàn dữ liệu lịch sử nạp thẻ, mua gói, tiến trình học tập và log kiểm toán. |
| **BR04** | **Phạm vi chọn tất cả** | Checkbox chọn tất cả chỉ áp dụng trên trang hiện tại, không chọn sang các trang khác. |
| **BR05** | **Thu hồi phiên làm việc** | Ngay khi tài khoản bị xóa mềm, hệ thống lập tức thu hồi toàn bộ token và phiên đăng nhập của tài khoản đó trên mọi ứng dụng/thiết bị. |

 

 

# **11\. Chức năng xem danh sách tài khoản học viên**

## **11.1. Thông tin chung**

| Mục | Nội dung |
| :---- | :---- |
| **Mã chức năng** | ADM-USER-11 |
| **Tên chức năng** | Xem danh sách tài khoản học viên (View Learner List) |
| **Tác nhân** | Quản trị viên (Admin) |
| **Mục tiêu** | Hiển thị danh sách toàn bộ tài khoản học viên trong hệ thống cùng thông tin gói cước, trạng thái và thời gian đăng nhập để phục vụ theo dõi, quản trị. |
| **Tiền điều kiện** | Quản trị viên đã đăng nhập thành công vào Cổng Quản trị. |
| **Hậu điều kiện** | Danh sách tài khoản học viên hiển thị đầy đủ trên màn hình. |

 

 

## **11.2. Luồng chính (Main Flow)**

| Bước | Tác nhân | Hành động |
| ----- | :---- | :---- |
| **1** | Quản trị viên | Bấm vào mục **Học viên** của mục Quản trị tài khoản. |
| **2** | Hệ thống | Lấy dữ liệu danh sách học viên từ cơ sở dữ liệu. |
| **3** | Hệ thống | Hiển thị bảng danh sách học viên gồm các cột thông tin: **Ảnh đại diện, Mã học viên, Tên đăng nhập, Email, Số điện thoại, Vai trò, Trạng thái, Gói tài khoản, Đăng nhập gần nhất**. |
| **4** | Quản trị viên | Xem thông tin danh sách; use case kết thúc. |

 

 

## **11.3. Luồng rẽ nhánh & Ngoại lệ (Alternative & Exception Flows)**

| Mã luồng | Điều kiện kích hoạt | Hành động xử lý của Hệ thống |
| :---- | :---- | :---- |
| **AF-01** | Chưa có dữ liệu tài khoản học viên nào | Hiển thị thông báo: \*"Không có dữ liệu tài khoản học viên"\*. |
| **AF-02** | Mất kết nối mạng / Lỗi máy chủ | Báo lỗi: \*"Không thể tải danh sách tài khoản học viên. Vui lòng thử lại sau"\*. Dừng use case. |

 

 

## **11.4. Ràng buộc dữ liệu (Validation Rules \- VR)**

| Mã VR | Trường dữ liệu | Quy tắc hiển thị |
| :---- | :---- | :---- |
| **VR01** | Ảnh (Avatar) | Hiển thị ảnh đại diện của học viên (thumbnail tròn); nếu chưa có ảnh hiển thị ảnh mặc định hệ thống. |
| **VR02** | Mã học viên | Mã định danh duy nhất của học viên trong hệ thống. |
| **VR03** | Tên đăng nhập | Tên tài khoản định danh duy nhất của học viên dùng để đăng nhập. |
| **VR04** | Email | Hiển thị địa chỉ email của học viên; nếu chưa có hiển thị Chưa cập nhật. |
| **VR05** | Số điện thoại | Hiển thị số điện thoại của học viên; nếu chưa có hiển thị Chưa cập nhật. |
| **VR06** | Vai trò | Hiển thị vai trò tài khoản trong hệ thống (mặc định: Học viên). |
| **VR07** | Trạng thái | Hiển thị 1 trong 2 trạng thái: **Hoạt động** (nhãn màu xanh) hoặc **Khóa** (nhãn màu đỏ/xám). |
| **VR08** | Gói tài khoản | Hiển thị tên gói cước dịch vụ đang kích hoạt (Ví dụ: Miễn phí, VIP 1 tháng, VIP 1 năm, Trọn đời). |
| **VR09** | Đăng nhập gần nhất | • Hiển thị mốc thời gian học viên đăng nhập gần nhất định dạng: DD/MM/YYYY HH:mm:ss. • Nếu chưa từng đăng nhập, hiển thị: Chưa đăng nhập. |

 

 

## **11.5. Quy tắc nghiệp vụ (Business Rules \- BR)**

| Mã BR | Tên quy tắc | Nội dung chi tiết |
| :---- | :---- | :---- |
| **BR01** | **Phạm vi hiển thị** | Hệ thống chỉ lọc và hiển thị danh sách người dùng là người học (role \= LEARNER), không bao gồm tài khoản quản trị nội bộ. |
| **BR02** | **Xác định gói tài khoản** | • Mặc định khi tài khoản mới đăng ký là gói **Miễn phí**. • Đối với tài khoản đăng ký gói trả phí, hiển thị gói dịch vụ còn hạn cao nhất tại thời điểm xem; khi gói trả phí hết hạn, hệ thống tự động chuyển về hiển thị gói **Miễn phí**. |
| **BR03** | **Cập nhật đăng nhập gần nhất** | Cột "Đăng nhập gần nhất" lấy từ trường last\_login\_at của tài khoản học viên, tự động cập nhật mỗi lần học viên đăng nhập thành công. |
| **BR04** | **Phân trang dữ liệu** | Hỗ trợ phân trang danh sách (mặc định 10 đến 20 học viên/trang) và sắp xếp mặc định theo thời gian tạo tài khoản mới nhất. |

 

 

# **12\. Chức năng xem thông tin chi tiết học viên**

## **12.1. Thông tin chung**

| Mục | Nội dung |
| :---- | :---- |
| **Mã chức năng** | ADM-USER-12 |
| **Tên chức năng** | Xem thông tin chi tiết học viên (View Learner Details) |
| **Tác nhân** | Quản trị viên (Admin) |
| **Mục tiêu** | Tra cứu toàn diện hồ sơ học viên qua 6 nhóm thông tin: Đăng ký, Bảo mật, Gói cước, Tài nguyên, Tiến độ học tập và Nhật ký hoạt động. |
| **Tiền điều kiện** | Đang ở màn hình Danh sách tài khoản học viên (ADM-USER-11). |
| **Hậu điều kiện** | Màn hình hiển thị đầy đủ 6 nhóm thông tin chi tiết của học viên. |

 

 

## **12.2. Luồng chính (Main Flow)**

| Bước | Tác nhân | Hành động |
| ----- | :---- | :---- |
| **1** | Quản trị viên | Bấm chọn 1 học viên trong danh sách. |
| **2** | Hệ thống | Truy xuất toàn bộ dữ liệu học viên từ CSDL. |
| **3** | Hệ thống | Hiển thị 6 nhóm thông tin: Thông tin đăng ký, Trạng thái & bảo mật, Gói & quyền lợi, Tài nguyên & điểm thưởng, Học tập, Nhật ký hoạt động. |
| **4** | Quản trị viên | Xem thông tin; kết thúc use case. |

 

 

## **12.3. Luồng rẽ nhánh & Ngoại lệ (Alternative & Exception Flows)**

| Mã luồng | Điều kiện kích hoạt | Xử lý của Hệ thống |
| :---- | :---- | :---- |
| **AF-01** | Học viên không tồn tại / đã bị xóa | Báo lỗi: \*"Không tìm thấy thông tin tài khoản"\*. Quay lại danh sách. |
| **AF-02** | Mục chưa có dữ liệu phát sinh | Hiển thị: \*"Chưa có dữ liệu"\* hoặc giá trị mặc định (0 bài, 0 trận, Chưa thi HSK). |
| **AF-03** | Mất kết nối mạng / Lỗi máy chủ | Báo lỗi: \*"Không thể tải dữ liệu. Vui lòng thử lại sau"\*. Dừng use case. |

 

 

## **12.4. Ràng buộc dữ liệu (Validation Rules \- VR)**

### **Nhóm 1 — Thông tin đăng ký**

| Mã VR | Trường dữ liệu | Quy tắc hiển thị |
| :---- | :---- | :---- |
| **VR01** | Ảnh đại diện | Hiển thị avatar học viên; nếu chưa có hiển thị ảnh mặc định. |
| **VR02** | Mã học viên | Mã định danh duy nhất (Read-only). |
| **VR03** | Tên đăng nhập | Tên tài khoản đăng nhập (Read-only). |
| **VR04** | Email / SĐT | Thông tin liên hệ (trường trống hiển thị: Chưa cập nhật). |
| **VR05** | Ngày tạo / Cập nhật | Định dạng: DD/MM/YYYY HH:mm:ss. |

 

### **Nhóm 2 — Trạng thái & Bảo mật**

| Mã VR | Trường dữ liệu | Quy tắc hiển thị |
| :---- | :---- | :---- |
| **VR06** | Trạng thái tài khoản | 1 trong 3 trạng thái: **Active** (xanh), **Locked** (cam), **Banned** (đỏ). |
| **VR07** | Tạm khóa đến | Mốc thời gian DD/MM/YYYY HH:mm:ss (nếu không: Không). |
| **VR08** | Đăng nhập gần nhất | Mốc thời gian DD/MM/YYYY HH:mm:ss (chưa đăng nhập: Chưa đăng nhập). |
| **VR09** | Thiết bị đã ghi nhớ | Bảng con: **Tên thiết bị, Nền tảng (iOS/Android/Web), Hoạt động gần nhất**. |

 

### **Nhóm 3 — Gói & Quyền lợi**

| Mã VR | Trường dữ liệu | Quy tắc hiển thị |
| :---- | :---- | :---- |
| **VR10** | Gói tài khoản | **Free** hoặc **Premium**. |
| **VR11** | Hạn Premium | Mốc hết hạn DD/MM/YYYY HH:mm:ss (gói Free: Không áp dụng). |
| **VR12** | Quyền lợi kèm theo | Tóm tắt quyền lợi tương ứng theo gói hiện tại. |

 

### **Nhóm 4 — Tài nguyên & Điểm thưởng**

| Mã VR | Trường dữ liệu | Quy tắc hiển thị |
| :---- | :---- | :---- |
| **VR13** | XP tổng / Level | Tổng điểm XP tích lũy và Cấp độ hiện tại. |
| **VR14** | Streak / Khiên | Số ngày học liên tục và Số khiên bảo vệ Streak còn lại. |
| **VR15** | Gems / Energy | Số lượng Gems và Energy hiện tại / tối đa (Premium: Vô hạn). |

 

### **Nhóm 5 — Học tập**

| Mã VR | Khối dữ liệu | Quy tắc hiển thị |   |   |
| :---- | :---- | :---- | :---- | ----- |
| **VR16** | **Tiến độ lộ trình** | Hiển thị dạng chữ chỉ đọc: Đã hoàn thành x/y bài \\ | Tỷ lệ: ...% \\ | Bài đang học: \[Tên bài\]. \*(Không dùng thanh tiến độ)\* |
| **VR17** | **Thi đấu (PvP)** | Tổng số trận \\ | Tỷ lệ thắng: ...% \\ | Hạng đấu hiện tại (Đồng, Bạc, Vàng, Bạch Kim, Kim Cương). |
| **VR18** | **HSK** | Cấp độ đang học (HSK 1–6) \\ | Điểm thi thử cao nhất từng HSK (dạng Điểm đạt / Điểm tối đa). |   |

 

### **Nhóm 6 — Nhật ký hoạt động**

| Mã VR | Thành phần | Quy tắc hiển thị |
| :---- | :---- | :---- |
| **VR19** | Bảng nhật ký | Bảng 5 cột: **Thời gian, Loại hoạt động, Nội dung chi tiết, IP, Trạng thái**. |

 

 

## **12.5. Quy tắc nghiệp vụ (Business Rules \- BR)**

| Mã BR | Tên quy tắc | Nội dung chi tiết |
| :---- | :---- | :---- |
| **BR01** | **Chế độ chỉ xem** | Toàn bộ màn hình thuần túy chỉ đọc (Read-only), không cho sửa trực tiếp. |
| **BR02** | **Nguồn dữ liệu học tập** | Tự động tổng hợp từ bảng tiến độ học (user\_learning\_progress), thi đấu (pvp\_matches) và thi thử HSK (hsk\_exam\_results). |
| **BR03** | **Sắp xếp & Phân trang** | Thiết bị và nhật ký sắp xếp mới nhất lên đầu; hỗ trợ phân trang bảng nhật ký. |

 

 

# **13\. Chức năng thêm tài khoản admin và học viên**

## **13.1. Thông tin chung**

| Mục | Nội dung |
| :---- | :---- |
| **Mã chức năng** | ADM-USER-13 |
| **Tên chức năng** | Thêm mới tài khoản (Create Account) |
| **Tác nhân** | Quản trị viên (Admin) |
| **Mục tiêu** | Tạo mới tài khoản Admin hoặc Học viên vào hệ thống với các thông số thiết lập ban đầu. |
| **Tiền điều kiện** | Quản trị viên đang ở màn hình Quản trị tài khoản (Danh sách Admin hoặc Danh sách Học viên). |
| **Hậu điều kiện** | Tài khoản mới được lưu vào CSDL kèm các thông số mặc định tương ứng với vai trò. |

 

 

## **13.2. Luồng chính (Main Flow)**

| Bước | Tác nhân | Hành động |
| ----- | :---- | :---- |
| **1** | Quản trị viên | Bấm nút **"THÊM TÀI KHOẢN"**. |
| **2** | Hệ thống | Hiển thị form nhập liệu gồm: Tên đăng nhập, Email, Số điện thoại, URL ảnh đại diện, Tải ảnh đại diện, Vai trò (Role), Trạng thái, Mật khẩu, Xác nhận mật khẩu. |
| **3** | Quản trị viên | Nhập đầy đủ thông tin vào form và bấm nút **"LƯU"**. |
| **4** | Hệ thống | Kiểm tra tính hợp lệ dữ liệu (theo VR) và kiểm tra tính duy nhất (theo BR). |
| **5** | Hệ thống | Mã hóa mật khẩu, gán các giá trị mặc định theo vai trò (theo BR) và lưu tài khoản vào CSDL. |
| **6** | Hệ thống | Thông báo: \*"Thêm tài khoản thành công"\*, chuyển hướng về danh sách tương ứng; use case kết thúc. |

 

 

## **13.3. Luồng rẽ nhánh & Ngoại lệ (Alternative & Exception Flows)**

| Mã luồng | Điều kiện kích hoạt | Hành động xử lý của Hệ thống |
| :---- | :---- | :---- |
| **AF-01** | Bỏ trống thông tin bắt buộc / Sai định dạng (Vi phạm VR01-VR07) | Báo lỗi tương ứng dưới từng ô nhập liệu. Dừng use case. |
| **AF-02** | Tên đăng nhập, Email hoặc SĐT đã tồn tại (Vi phạm BR01) | Báo lỗi: \*"Tên đăng nhập, Email hoặc Số điện thoại đã tồn tại trên hệ thống"\*. Dừng use case. |
| **AF-03** | Xác nhận mật khẩu không khớp (Vi phạm VR08) | Báo lỗi: \*"Mật khẩu xác nhận không khớp"\*. Dừng use case. |
| **AF-04** | Mất kết nối mạng / Lỗi máy chủ | Báo lỗi: \*"Không thể tạo tài khoản. Vui lòng thử lại sau"\*. Dừng use case. |

 

 

## **13.4. Ràng buộc dữ liệu (Validation Rules \- VR)**

| Mã VR | Trường dữ liệu | Quy tắc kiểm tra | Thông báo lỗi |
| :---- | :---- | :---- | :---- |
| **VR01** | Tên đăng nhập | • Bắt buộc nhập. • Tối thiểu 4 ký tự, viết liền không dấu, không chứa khoảng trắng. | \*"Tên đăng nhập tối thiểu 4 ký tự, viết liền không dấu"\* |
| **VR02** | Email | • Bắt buộc nhập. • Đúng định dạng email chuẩn (...@{domain}). | \*"Email không đúng định dạng"\* |
| **VR03** | Số điện thoại | • Bắt buộc nhập. • Chuẩn 10 chữ số. | \*"Số điện thoại phải gồm 10 chữ số"\* |
| **VR04** | Ảnh đại diện | • Tùy chọn (dán link URL hoặc tải file ảnh .png, .jpg, dung lượng \<= 5MB). • Nếu để trống: hệ thống tự động gán ảnh mặc định. | \*"Định dạng ảnh hoặc dung lượng không hợp lệ"\* |
| **VR05** | Vai trò (Role) | • Bắt buộc chọn 1 trong 2: **Admin** hoặc **Học viên** (mặc định: Học viên). | \*"Vui lòng chọn vai trò"\* |
| **VR06** | Trạng thái | • Bắt buộc chọn 1 trong 2: **Hoạt động** hoặc **Khóa** (mặc định: Hoạt động). | \*"Vui lòng chọn trạng thái"\* |
| **VR07** | Mật khẩu | • Bắt buộc nhập. • Tối thiểu 8 ký tự, ẩn dạng •••••• kèm icon 👁️ xem mật khẩu. | \*"Mật khẩu phải có tối thiểu 8 ký tự"\* |
| **VR08** | Xác nhận mật khẩu | • Bắt buộc nhập. • Khớp 100% với Mật khẩu. | \*"Mật khẩu xác nhận không khớp"\* |

 

 

## **13.5. Quy tắc nghiệp vụ (Business Rules \- BR)**

| Mã BR | Tên quy tắc | Nội dung chi tiết |
| :---- | :---- | :---- |
| **BR01** | **Tính duy nhất dữ liệu** | Tên đăng nhập, Email và Số điện thoại là duy nhất trong toàn hệ thống, không được trùng lặp với bất kỳ tài khoản nào khác. |
| **BR02** | **Mã hóa an toàn** | Mật khẩu bắt buộc được mã hóa bằng thuật toán an toàn (BCrypt/Argon2) trước khi lưu vào CSDL. |
| **BR03** | **Tự sinh mã định danh** | Tự động sinh mã tài khoản duy nhất theo vai trò: Admin dạng ADMxxxxx, Học viên dạng HVxxxxx. |
| **BR04** | **Thông số mặc định cho tài khoản ADMIN** | • Quyền hạn: is\_admin \= TRUE, cấp quyền truy cập Cổng Quản trị. • Các chỉ số học tập, gói cước, Gems, Streak, Energy: **để trống (\`NULL\`)**, không tạo bản ghi user\_stats. • Lần đăng nhập gần nhất: **để trống (\`NULL\`)**. |
| **BR05** | **Thông số mặc định cho tài khoản HỌC VIÊN** | • **Quyền hạn**: is\_admin \= FALSE, role \= LEARNER. • **Gói tài khoản**: Mặc định là gói **Free** (tier \= 'free'), ngày hết hạn Premium **để trống (\`NULL\`)**. • **Chỉ số Gems & Energy**: Tự động gán theo giá trị định mức chuẩn do Admin cấu hình trong hệ thống cho loại tài khoản Free (mức Energy tối đa và số Gems khởi tạo ban đầu). • **Các chỉ số học tập khác**: XP \= 0, Level \= 1, Streak \= 0, Số Khiên \= 0, Hạng đấu \= Đồng. • **Lịch sử đăng nhập & Tạm khóa**: **để trống (\`NULL\`)**. |

 

 

# **14\. Chức năng sửa thông tin chi tiết học viên**

## **14.1. Thông tin chung**

| Mục | Nội dung |
| :---- | :---- |
| **Mã chức năng** | ADM-USER-14 |
| **Tên chức năng** | Sửa thông tin chi tiết học viên (Edit Learner Details) |
| **Tác nhân** | Quản trị viên (Admin) |
| **Mục tiêu** | Cho phép Admin chỉnh sửa thông tin đăng ký, trạng thái, gói cước, tài nguyên; đồng thời xem các chỉ số học tập (tiến độ, thi đấu, HSK) ở chế độ chỉ đọc. |
| **Tiền điều kiện** | Đang ở màn hình Danh sách tài khoản học viên (ADM-USER-11). |
| **Hậu điều kiện** | Dữ liệu cập nhật vào CSDL và hệ thống tự động ghi nhật ký thay đổi. |

 

 

## **14.2. Luồng chính (Main Flow)**

| Bước | Tác nhân | Hành động |
| ----- | :---- | :---- |
| **1** | Quản trị viên | Bấm chọn 1 học viên từ danh sách. |
| **2** | Hệ thống | Hiển thị form chi tiết với dữ liệu hiện tại (cho phép sửa Nhóm 1, 2, 3, 4; Nhóm 5 Học tập và Nhóm 6 Nhật ký hiển thị Chỉ đọc). |
| **3** | Quản trị viên | Nhập/chọn thông tin mới vào các trường cho phép sửa và bấm **"LƯU"**. |
| **4** | Hệ thống | Kiểm tra hợp lệ dữ liệu (VR) và kiểm tra tính duy nhất (BR). |
| **5** | Hệ thống | Cập nhật CSDL, cập nhật updated\_at, ghi nhật ký kiểm toán, thông báo: \*"Cập nhật thông tin thành công"\*; kết thúc use case. |

 

 

## **14.3. Luồng rẽ nhánh & Ngoại lệ (Alternative & Exception Flows)**

| Mã luồng | Điều kiện kích hoạt | Xử lý của Hệ thống |
| :---- | :---- | :---- |
| **AF-01** | Bỏ trống / Sai định dạng bắt buộc | Báo lỗi ngay dưới ô nhập liệu. Dừng use case. |
| **AF-02** | Trùng Tên đăng nhập, Email hoặc SĐT | Báo lỗi: \*"Thông tin đã được sử dụng bởi tài khoản khác"\*. Dừng use case. |
| **AF-03** | Chọn Premium nhưng thiếu hạn dùng | Báo lỗi: \*"Vui lòng chọn ngày hết hạn cho gói Premium"\*. Dừng use case. |
| **AF-04** | Nhập số âm ở trường tài nguyên | Báo lỗi: \*"Giá trị phải lớn hơn hoặc bằng 0"\*. Dừng use case. |
| **AF-05** | Bấm "QUAY LẠI" khi chưa lưu | Cảnh báo: \*"Thay đổi chưa lưu sẽ bị mất. Bạn có chắc muốn quay lại?"\*. |
| **AF-06** | Mất kết nối mạng / Lỗi máy chủ | Báo lỗi: \*"Không thể lưu thay đổi. Vui lòng thử lại sau"\*. Dừng use case. |

 

 

## **14.4. Ràng buộc dữ liệu (Validation Rules \- VR)**

### **Nhóm 1 — Thông tin đăng ký**

| Mã VR | Trường dữ liệu | Chế độ | Quy tắc kiểm tra |
| :---- | :---- | :---- | :---- |
| **VR01** | Mã học viên | **Chỉ đọc** | Mã định danh duy nhất, không cho phép sửa. |
| **VR02** | Tên đăng nhập | **Sửa** | Bắt buộc nhập, tối thiểu 4 ký tự, viết liền không dấu. |
| **VR03** | Email / SĐT | **Sửa** | Bắt buộc nhập, đúng định dạng email và 10 số điện thoại. |
| **VR04** | Ảnh đại diện | **Sửa** | Cho phép dán URL hoặc tải file ảnh (.png, .jpg \<= 5MB). |
| **VR05** | Ngày tạo / Cập nhật | **Chỉ đọc** | Hệ thống tự động ghi nhận thời gian. |

 

### **Nhóm 2 — Trạng thái & Bảo mật**

| Mã VR | Trường dữ liệu | Chế độ | Quy tắc kiểm tra |
| :---- | :---- | :---- | :---- |
| **VR06** | Trạng thái tài khoản | **Sửa** | Dropdown chọn: **Active**, **Locked**, **Banned**. |
| **VR07** | Tạm khóa đến / Đăng nhập gần nhất / Thiết bị | **Chỉ đọc** | Dữ liệu hệ thống theo dõi, không sửa trực tiếp. |

 

### **Nhóm 3 — Gói & Quyền lợi**

| Mã VR | Trường dữ liệu | Chế độ | Quy tắc kiểm tra |
| :---- | :---- | :---- | :---- |
| **VR08** | Gói tài khoản | **Sửa** | Dropdown chọn: **Free** hoặc **Premium**. |
| **VR09** | Hạn Premium | **Sửa** | Bắt buộc chọn ngày tương lai nếu là Premium; xóa trống (NULL) nếu là Free. |
| **VR10** | Quyền lợi | **Chỉ đọc** | Tự động hiển thị nội dung theo gói được chọn. |

 

### **Nhóm 4 — Tài nguyên & Điểm thưởng**

| Mã VR | Trường dữ liệu | Chế độ | Quy tắc kiểm tra |
| :---- | :---- | :---- | :---- |
| **VR11** | XP tổng / Level | **Sửa** | Nhập số nguyên: XP \>= 0, Level \>= 1. |
| **VR12** | Streak / Khiên | **Sửa** | Nhập số nguyên: Streak \>= 0, Số Khiên \>= 0. |
| **VR13** | Gems / Energy | **Sửa** | Nhập số nguyên: Gems \>= 0, Energy \>= 0 (Premium: Vô hạn). |

 

### **Nhóm 5 — Học tập (CHỈ ĐỌC)**

| Mã VR | Khối dữ liệu | Chế độ | Quy tắc kiểm tra |   |   |
| :---- | :---- | :---- | :---- | :---- | ----- |
| **VR14** | **Tiến độ lộ trình** | **Chỉ đọc** | Hiển thị: Hoàn thành x/y bài \\ | Tỷ lệ: ...% \\ | Bài đang học. Khóa ô, không cho sửa, không dùng thanh tiến độ. |
| **VR15** | **Thi đấu (PvP)** | **Chỉ đọc** | Hiển thị: Tổng số trận \\ | Tỷ lệ thắng: ...% \\ | Hạng đấu hiện tại. Khóa ô, không cho sửa. |
| **VR16** | **HSK** | **Chỉ đọc** | Hiển thị: Cấp độ đang học \\ | Điểm thi thử cao nhất từng HSK. Khóa ô, không cho sửa. |   |

 

### **Nhóm 6 — Nhật ký hoạt động**

| Mã VR | Thành phần | Chế độ | Quy tắc |
| :---- | :---- | :---- | :---- |
| **VR17** | Lịch sử nhật ký | **Chỉ đọc** | Không cho sửa; hệ thống tự động ghi bản ghi mới khi bấm Lưu. |

 

 

## **14.5. Quy tắc nghiệp vụ (Business Rules \- BR)**

| Mã BR | Tên quy tắc | Nội dung chi tiết |
| :---- | :---- | :---- |
| **BR01** | **Tính duy nhất** | Tên đăng nhập, Email, SĐT không được trùng với bất kỳ tài khoản nào khác trong hệ thống. |
| **BR02** | **Ràng buộc gói Premium** | Chuyển sang Premium bắt buộc nhập ngày hết hạn; chuyển về Free tự động xóa hạn (NULL). |
| **BR03** | **Xử lý phiên khi đổi trạng thái** | Chuyển sang Locked hoặc Banned sẽ thu hồi ngay toàn bộ phiên đăng nhập của học viên trên mọi thiết bị. |
| **BR04** | **Bảo toàn dữ liệu học tập** | Toàn bộ dữ liệu nhóm **Học tập** (Tiến độ, Thi đấu, Điểm HSK) là **Chỉ đọc (Read-only)**, phản ánh kết quả học thực tế, tuyệt đối không cho phép can thiệp thủ công. |
| **BR05** | **Ghi nhật ký kiểm toán** | Tự động ghi vết vào bảng audit\_logs (giá trị cũ, giá trị mới, ID admin thực hiện, thời gian). |

 

 

# **15\. Chức năng xem danh sách gói cước**

## **15.1. Thông tin chung**

| Mục | Nội dung |
| :---- | :---- |
| **Mã chức năng** | ADM-SUB-01 |
| **Tên chức năng** | Xem danh sách gói cước (View Subscription Package List) |
| **Tác nhân** | Quản trị viên (Super Admin, Admin) |
| **Mục tiêu** | Hiển thị danh sách các gói cước trong hệ thống để theo dõi hạng gói, kỳ hạn và số lượng người học đang sử dụng. |
| **Tiền điều kiện** | Quản trị viên đã đăng nhập vào Cổng Quản trị CMS. |
| **Hậu điều kiện** | Bảng danh sách gói cước hiển thị đầy đủ trên màn hình. |

 

 

## **15.2. Luồng chính (Main Flow)**

| Bước | Tác nhân | Hành động |
| ----- | :---- | :---- |
| **1** | Quản trị viên | Bấm chọn mục **"Quản lý gói cước"** trên menu điều hướng. |
| **2** | Hệ thống | Truy vấn CSDL và đếm số lượng người dùng đang kích hoạt từng gói. |
| **3** | Hệ thống | Hiển thị bảng danh sách gồm 6 cột: **Mã gói, Tên gói, Hạng, Kỳ hạn, Đang dùng, Trạng thái**. |
| **4** | Quản trị viên | Xem thông tin danh sách; use case kết thúc. |

 

 

## **15.3. Luồng rẽ nhánh & Ngoại lệ (Alternative Flows)**

| Mã luồng | Điều kiện | Xử lý của Hệ thống |
| :---- | :---- | :---- |
| **AF-01** | Chưa có gói cước nào trong hệ thống | Hiển thị bảng trống kèm thông báo: \*"Chưa có dữ liệu gói cước"\*. |
| **AF-02** | Mất kết nối mạng / Lỗi máy chủ | Báo lỗi: \*"Không thể tải danh sách gói cước. Vui lòng thử lại sau"\*; dừng use case. |

 

 

## **15.4. Ràng buộc dữ liệu (Validation Rules \- VR)**

| Mã VR | Cột hiển thị | Kiểu hiển thị | Quy tắc hiển thị chi tiết |
| :---- | :---- | :---- | :---- |
| **VR01** | **Mã gói** | Dạng chữ | Mã định danh duy nhất (VD: PKG00001). |
| **VR02** | **Tên gói** | Dạng chữ | Tên đầy đủ của gói cước (VD: Gói HeloChina Premium). |
| **VR03** | **Hạng** | Nhãn (Badge) | Hiển thị hạng gói: **Free** (xám) hoặc **Premium** (vàng/cam). |
| **VR04** | **Kỳ hạn** | Dạng chữ | Chu kỳ sử dụng: **Theo Tháng (30 ngày)**, **Theo Năm (365 ngày)** hoặc **Vô thời hạn** (cho Free). |
| **VR05** | **Đang dùng** | Số nguyên | Tổng số học viên hiện đang kích hoạt gói này (VD: 1.250). |
| **VR06** | **Trạng thái** | Nhãn màu | Hiển thị 1 trong 2 trạng thái: **Hoạt động** (nhãn xanh) hoặc **Tạm ngừng** (nhãn đỏ). |

 

 

## **15.5. Quy tắc nghiệp vụ (Business Rules \- BR)**

| Mã BR | Tên quy tắc | Nội dung chi tiết |
| :---- | :---- | :---- |
| **BR01** | **Tính toán số lượng "Đang dùng"** | Hệ thống tự động đếm (COUNT) số học viên có tài khoản đang kích hoạt gói này (status \= ACTIVE và expired\_at \>= Thời điểm hiện tại). |
| **BR02** | **Thứ tự sắp xếp** | Mặc định danh sách được sắp xếp tăng dần theo trường **Thứ tự hiển thị** (display\_order ASC). |
| **BR03** | **Chế độ chỉ xem** | Màn hình danh sách thuần túy để tra cứu; các thao tác khác (Thêm, Sửa...) được thực hiện qua các nút bấm riêng. |

 

 

# **16\. Chức năng thêm gói cước**

## **16.1. Thông tin chung**

| Mục | Nội dung |
| :---- | :---- |
| **Mã chức năng** | ADM-SUB-02 |
| **Tên chức năng** | Thêm gói cước (Create Subscription Package) |
| **Tác nhân** | Quản trị viên (Super Admin, Admin) |
| **Mục tiêu** | Tạo gói cước Premium mới với chu kỳ rõ ràng (Theo Tháng hoặc Theo Năm), thiết lập Giá bán thực tế và Giá gốc niêm yết để bán trên ứng dụng. |
| **Tiền điều kiện** | Đang ở màn hình Danh mục gói cước (ADM-SUB-01). |
| **Hậu điều kiện** | Gói cước được lưu vào CSDL và sẵn sàng hiển thị trên App nếu đang Hoạt động. |

 

 

## **16.2. Luồng chính (Main Flow)**

| Bước | Tác nhân | Hành động |
| ----- | :---- | :---- |
| **1** | Quản trị viên | Bấm **"+ Thêm gói cước"**. |
| **2** | Hệ thống | Mở form thêm mới (Mã gói tự sinh \- khóa ô; Chu kỳ mặc định: Theo Tháng; Trạng thái mặc định: Hoạt động). |
| **3** | Quản trị viên | Nhập/chọn rõ ràng các trường: • **Tên gói**: Tên gói cước. • **Chu kỳ cước**: Chọn **Theo Tháng (30 ngày)** hoặc **Theo Năm (365 ngày)**. • **Giá bán**: Số tiền thực tế thu của học viên (VNĐ). • **Giá gốc**: Số tiền niêm yết ban đầu để gạch ngang hiển thị giảm giá (VNĐ). • **Mô tả**: Thông tin quyền lợi nổi bật. • **Thứ tự hiển thị**: Vị trí xuất hiện trên App. • **Trạng thái**: Chọn Hoạt động hoặc Tạm ngừng. |
| **4** | Quản trị viên | Bấm **"LƯU"**. |
| **5** | Hệ thống | Kiểm tra hợp lệ dữ liệu (theo VR) và kiểm tra tính duy nhất (theo BR). |
| **6** | Hệ thống | Lưu CSDL, ghi log kiểm toán, báo: \*"Thêm gói cước thành công"\*; kết thúc use case. |

 

 

## **16.3. Luồng rẽ nhánh & Ngoại lệ (Alternative & Exception Flows)**

| Mã luồng | Điều kiện kích hoạt | Xử lý của Hệ thống |
| :---- | :---- | :---- |
| **AF-01** | Thiếu thông tin bắt buộc / Nhập số âm | Báo lỗi ngay dưới ô nhập liệu; dừng lưu. |
| **AF-02** | Trùng tên gói cước đã có | Báo lỗi: \*"Tên gói cước đã tồn tại"\*; dừng lưu. |
| **AF-03** | Giá gốc nhỏ hơn Giá bán thực tế | Báo lỗi: \*"Giá gốc niêm yết phải lớn hơn hoặc bằng Giá bán"\*; dừng lưu. |
| **AF-04** | Bấm "HỦY" | Đóng form, không lưu dữ liệu, quay lại danh sách. |

 

 

## **16.4. Ràng buộc dữ liệu (Validation Rules \- VR)**

| Mã VR | Trường dữ liệu | Chế độ | Bắt buộc | Quy tắc kiểm tra chi tiết | Thông báo lỗi |
| :---- | :---- | :---- | :---- | :---- | ----- |
| **VR01** | **Mã gói** | **Chỉ đọc** | \- | Tự sinh duy nhất dạng PKGxxxxx. Không cho sửa. | \- |
| **VR02** | **Tên gói** | Sửa | Có | Từ 3 đến 100 ký tự (VD: Gói Premium 1 Tháng, Gói Premium 1 Năm). | \*"Tên gói từ 3 đến 100 ký tự"\* |
| **VR03** | **Chu kỳ cước** | Sửa | Có | Dropdown chọn 1 trong 2 chu kỳ cố định: • **Theo Tháng**: Cố định thời hạn **30 ngày**. • **Theo Năm**: Cố định thời hạn **365 ngày**. | \*"Vui lòng chọn chu kỳ cước"\* |
| **VR04** | **Giá bán thực tế** | Sửa | Có | Số nguyên \> 0 (VNĐ) — Số tiền thực tế học viên phải thanh toán khi mua gói. | \*"Giá bán phải là số nguyên \> 0"\* |
| **VR05** | **Giá gốc niêm yết** | Sửa | Không | Số nguyên \>= Giá bán (VNĐ) — Giá niêm yết hiển thị gạch ngang (VD: \~\~250.000đ\~\~) để học viên thấy được ưu đãi giảm giá. | \*"Giá gốc phải \>= Giá bán"\* |
| **VR06** | **Mô tả** | Sửa | Không | Tối đa 255 ký tự (mô tả ưu đãi/điểm nổi bật). | \*"Mô tả tối đa 255 ký tự"\* |
| **VR07** | **Thứ tự hiển thị** | Sửa | Có | Số nguyên \>= 1 (xác định vị trí trên App; số nhỏ hiển thị trước). | \*"Thứ tự hiển thị phải \>= 1"\* |
| **VR08** | **Trạng thái** | Sửa | Có | Chọn: **Hoạt động** hoặc **Tạm ngừng** (Mặc định: Hoạt động). | \*"Vui lòng chọn trạng thái"\* |

 

 

## **16.5. Quy tắc nghiệp vụ (Business Rules \- BR)**

| Mã BR | Tên quy tắc | Nội dung chi tiết |
| :---- | :---- | :---- |
| **BR01** | **Mã gói định danh** | Tự sinh mã tăng dần PKGxxxxx, cố định không đổi để đối soát giao dịch và hóa đơn thanh toán. |
| **BR02** | **Tính duy nhất** | Tên gói cước không được trùng lặp với gói cước khác đang hoạt động. |
| **BR03** | **Xử lý thời hạn khi học viên mua** | Khi thanh toán thành công, hệ thống tự động cộng hạn dùng vào tài khoản: • Nếu mua gói **Theo Tháng**: hạn Premium \= thời điểm mua \+ 30 ngày. • Nếu mua gói **Theo Năm**: hạn Premium \= thời điểm mua \+ 365 ngày. |
| **BR04** | **Cơ chế hiển thị giá trên App** | Client (App) hiển thị giá rõ ràng theo công thức: • **Giá bán**: Hiển thị đậm làm giá thanh toán chính thức. • **Giá gốc (nếu có)**: Hiển thị gạch ngang (\~\~Giá gốc\~\~) kèm nhãn % tiết kiệm: ((Giá gốc \- Giá bán) / Giá gốc) \* 100%. |
| **BR05** | **Trạng thái hiển thị** | • Hoạt động: Gói cước xuất hiện ngay trên Paywall theo thứ tự hiển thị. • Tạm ngừng: Ẩn hoàn toàn khỏi màn hình mua gói của học viên. |
| **BR06** | **Nhật ký kiểm toán** | Ghi nhận sự kiện tạo gói vào bảng audit\_logs (admin\_id, thời gian, chi tiết các thông số của gói cước mới). |

 

 

# **17\. Chức năng xem và sửa chi tiết gói cước**

## **17.1. Thông tin chung**

| Mục | Nội dung |
| :---- | :---- |
| **Mã chức năng** | ADM-SUB-03 |
| **Tên chức năng** | Xem và sửa chi tiết gói cước (View & Edit Subscription Package Details) |
| **Tác nhân** | Quản trị viên (Super Admin, Admin) |
| **Mục tiêu** | Tra cứu toàn diện hồ sơ gói cước và cho phép chỉnh sửa thông tin thương mại, giá cả, kỳ hạn, trạng thái khi có yêu cầu. |
| **Tiền điều kiện** | Đang ở màn hình Danh mục gói cước (ADM-SUB-01). |
| **Hậu điều kiện** | Dữ liệu cập nhật vào CSDL, ghi nhận nhật ký kiểm toán và đồng bộ hiển thị lên ứng dụng. |

 

 

## **17.2. Luồng chính (Main Flow)**

| Bước | Tác nhân | Hành động |
| ----- | :---- | :---- |
| **1** | Quản trị viên | Bấm vào 1 gói cước trong danh sách. |
| **2** | Hệ thống | Hiển thị màn hình chi tiết với toàn bộ dữ liệu hiện tại (phân định rõ trường Sửa và trường Chỉ đọc). |
| **3** | Quản trị viên | Xem thông tin hoặc chỉnh sửa các trường cho phép (Tên, Hạng, Mô tả, Giá niêm yết, Giá bán, Kỳ hạn, Thứ tự, Trạng thái). |
| **4** | Quản trị viên | Bấm nút **"LƯU"**. |
| **5** | Hệ thống | Hiển thị popup xác nhận: \*"Bạn có chắc chắn muốn lưu các thay đổi của gói cước này?"\*. |
| **6** | Quản trị viên | Bấm **"XÁC NHẬN"**. |
| **7** | Hệ thống | Kiểm tra tính hợp lệ dữ liệu (theo VR) và kiểm tra tính duy nhất (theo BR). |
| **8** | Hệ thống | Cập nhật CSDL, cập nhật updated\_at, ghi log kiểm toán, thông báo: \*"Cập nhật gói cước thành công"\*; kết thúc use case. |

 

 

## **17.3. Luồng rẽ nhánh & Ngoại lệ (Alternative Flows)**

| Mã luồng | Điều kiện kích hoạt | Xử lý của Hệ thống |
| :---- | :---- | :---- |
| **AF-01: Cảnh báo chưa lưu** | Có thay đổi dữ liệu nhưng bấm "Quay lại" hoặc thoát trang | Hiển thị popup: \*"Dữ liệu đã thay đổi chưa được lưu. Bạn có chắc chắn muốn thoát và hủy bỏ các thay đổi?"\* • Chọn \*Rời đi\*: Hủy thay đổi, thoát về danh sách. • Chọn \*Ở lại\*: Giữ nguyên màn hình chỉnh sửa. |
| **AF-02: Hủy popup lưu** | Bấm "HỦY" tại popup xác nhận lưu | Đóng popup, giữ nguyên dữ liệu đang sửa trên form. |
| **AF-03: Vi phạm dữ liệu** | Bỏ trống trường bắt buộc / Sai định dạng (VR) | Báo lỗi ngay dưới ô nhập liệu; dừng thao tác lưu. |
| **AF-04: Trùng tên gói** | Sửa trùng tên với gói khác đang hoạt động | Báo lỗi: \*"Tên gói cước đã tồn tại"\*; dừng thao tác lưu. |

 

 

## **17.4. Ràng buộc dữ liệu (Validation Rules \- VR)**

| Mã VR | Trường dữ liệu | Chế độ | Bắt buộc | Quy tắc kiểm tra chi tiết | Thông báo lỗi |
| :---- | :---- | :---- | :---- | :---- | ----- |
| **VR01** | **Mã gói** | **Chỉ đọc** | \- | Mã duy nhất dạng PKGxxxxx, khóa ô, không cho sửa. | \- |
| **VR02** | **Tên gói** | **Sửa** | Có | Độ dài từ 3 đến 100 ký tự. | \*"Tên gói từ 3 đến 100 ký tự"\* |
| **VR03** | **Hạng tài khoản** | **Sửa** | Có | Dropdown chọn: **Free** hoặc **Premium**. | \*"Vui lòng chọn hạng tài khoản"\* |
| **VR04** | **Mô tả** | **Sửa** | Không | Tối đa 255 ký tự (nội dung quyền lợi nổi bật). | \*"Mô tả tối đa 255 ký tự"\* |
| **VR05** | **Giá niêm yết (Gốc)** | **Sửa** | Không | Số nguyên \>= Giá bán (VNĐ) — dùng để gạch ngang hiển thị giảm giá trên App. | \*"Giá niêm yết phải \>= Giá bán"\* |
| **VR06** | **Giá bán thực tế** | **Sửa** | Có | Số nguyên \>= 0 (VNĐ) — tiền thực tế học viên phải trả. Gói Free bắt buộc là 0. | \*"Giá bán không hợp lệ"\* |
| **VR07** | **Kỳ hạn** | **Sửa** | Có | Dropdown chọn: **Theo Tháng (30 ngày)**, **Theo Năm (365 ngày)** hoặc **Vô thời hạn** (Free). | \*"Vui lòng chọn kỳ hạn"\* |
| **VR08** | **Thứ tự hiển thị** | **Sửa** | Có | Số nguyên \>= 1 (số nhỏ hiển thị trước trên App). | \*"Thứ tự hiển thị phải \>= 1"\* |
| **VR09** | **Trạng thái** | **Sửa** | Có | Dropdown chọn: **Hoạt động** hoặc **Tạm ngừng**. | \*"Vui lòng chọn trạng thái"\* |
| **VR10** | **Ngày tạo / Cập nhật** | **Chỉ đọc** | \- | Định dạng DD/MM/YYYY HH:mm:ss, do hệ thống tự động ghi nhận. | \- |
| **VR11** | **Số người đang dùng** | **Chỉ đọc** | \- | Tổng số học viên hiện đang kích hoạt gói này (status \= ACTIVE). | \- |
| **VR12** | **Danh sách quyền lợi** | **Chỉ đọc** | \- | Tự động tải và hiển thị danh sách quyền lợi tương ứng theo **Hạng tài khoản** (được quy định từ Ma trận quyền lợi). | \- |

 

 

## **17.5. Quy tắc nghiệp vụ (Business Rules \- BR)**

| Mã BR | Tên quy tắc | Nội dung chi tiết |
| :---- | :---- | :---- |
| **BR01** | **Cơ chế chống mất dữ liệu (Dirty Check)** | Hệ thống liên tục so khớp dữ liệu hiện tại với dữ liệu ban đầu. Nếu phát hiện có bất kỳ sự thay đổi nào mà người dùng thao tác thoát/quay lại, hệ thống **bắt buộc hiển thị popup cảnh báo xác nhận** để tránh mất dữ liệu hoặc thao tác nhầm. |
| **BR02** | **Xác nhận 2 bước khi Lưu** | Bắt buộc phải có popup xác nhận trước khi cập nhật CSDL nhằm đảm bảo quản trị viên đã kiểm tra kỹ các thông số giá và kỳ hạn trước khi áp dụng ra thị trường. |
| **BR03** | **Đồng bộ quyền lợi theo Ma trận** | Khối thông tin \*"Danh sách các quyền lợi"\* là thuần túy chỉ đọc, được hệ thống tự động ánh xạ từ cấu hình **Ma trận quyền lợi** của hệ thống tương ứng theo Hạng tài khoản (Free/Premium). |
| **BR04** | **Không ảnh hưởng gói học viên đã mua** | Thay đổi Giá bán hoặc Kỳ hạn chỉ áp dụng cho các lượt mua mới sau thời điểm lưu; học viên đã thanh toán trước đó vẫn giữ nguyên thời hạn đến khi hết chu kỳ. |
| **BR05** | **Nhật ký kiểm toán** | Tự động ghi nhận chi tiết bản ghi vào audit\_logs (admin\_id, thời gian, chi tiết giá trị cũ và giá trị mới của các trường bị thay đổi). |

 

 

# **18\. Chức năng xem danh sách quyền lợi**

## **18.1. Thông tin chung**

| Mục | Nội dung |
| :---- | :---- |
| **Mã chức năng** | ADM-PRIV-01 |
| **Tên chức năng** | Xem danh sách quyền lợi (View Privilege List) |
| **Tác nhân** | Quản trị viên (Super Admin, Admin) |
| **Mục tiêu** | Tra cứu danh sách quyền lợi, nhóm phân loại, giá trị áp dụng theo gói và trạng thái. |
| **Tiền điều kiện** | Quản trị viên đã đăng nhập vào Cổng Quản trị CMS. |
| **Hậu điều kiện** | Bảng danh sách quyền lợi hiển thị đầy đủ trên màn hình. |

 

 

## **18.2. Luồng chính (Main Flow)**

| Bước | Tác nhân | Hành động |
| ----- | :---- | :---- |
| **1** | Quản trị viên | Chọn mục **"Danh mục quyền lợi"** trên menu điều hướng. |
| **2** | Hệ thống | Truy vấn CSDL và lấy cấu hình giá trị tương ứng theo từng gói từ Ma trận. |
| **3** | Hệ thống | Hiển thị bảng danh sách gồm 5 cột: **Mã quyền lợi, Tên quyền lợi, Nhóm, Giá trị theo gói, Trạng thái**. |
| **4** | Quản trị viên | Xem danh sách hoặc bấm vào 1 dòng để xem/sửa chi tiết. |

 

 

## **18.3. Luồng rẽ nhánh & Ngoại lệ (Alternative Flows)**

| Mã luồng | Điều kiện | Xử lý của Hệ thống |
| :---- | :---- | :---- |
| **AF-01** | Tìm kiếm / Lọc danh sách | Nhập Mã/Tên hoặc lọc theo Nhóm / Trạng thái → Cập nhật danh sách hiển thị tương ứng. |
| **AF-02** | Bấm "+ Thêm quyền lợi" | Điều hướng sang màn hình \*Thêm quyền lợi\* (ADM-PRIV-02). |
| **AF-03** | Bấm vào 1 dòng quyền lợi | Điều hướng sang màn hình \*Xem và sửa chi tiết quyền lợi\* (ADM-PRIV-03). |
| **AF-04** | Không có dữ liệu / Lỗi mạng | Báo lỗi tương ứng: \*"Không tìm thấy quyền lợi"\* hoặc \*"Không thể tải danh sách quyền lợi"\*. |

 

 

## **18.4. Ràng buộc dữ liệu (Validation Rules \- VR)**

| Mã VR | Cột hiển thị | Quy tắc hiển thị | Thông báo lỗi |
| :---- | :---- | :---- | :---- |
| **VR01** | **Mã quyền lợi** | Hiển thị mã kỹ thuật duy nhất dạng snake\_case (chỉ đọc). | — |
| **VR02** | **Tên quyền lợi** | Hiển thị tên quyền lợi đầy đủ; bấm vào tên để mở xem/sửa chi tiết. | — |
| **VR03** | **Nhóm** | Hiển thị tên nhóm quyền lợi (AI, Học tập, HSK...). Nếu chưa phân nhóm hiển thị —. | — |
| **VR04** | **Giá trị theo gói** | Hiển thị tóm tắt mức cấu hình tương ứng ở từng gói (VD: Free: 3 lượt/ngày \\ | Premium: Không giới hạn). Chưa cấu hình hiển thị —. |
| **VR05** | **Trạng thái** | Hiển thị 1 trong 2 trạng thái: **Đang dùng** (nhãn xanh) hoặc **Ngừng dùng** (nhãn xám). | — |

 

 

## **18.5. Quy tắc nghiệp vụ (Business Rules \- BR)**

| Mã BR | Tên quy tắc | Nội dung chi tiết |
| :---- | :---- | :---- |
| **BR01** | **Ánh xạ giá trị theo gói** | Tự động lấy giá trị từ Ma trận quyền lợi tương ứng theo gói; nếu chưa có thì hiển thị —. |
| **BR02** | **Thứ tự sắp xếp** | Mặc định sắp xếp tăng dần theo Thứ tự hiển thị, sau đó theo Ngày tạo mới nhất. |
| **BR03** | **Điều hướng nhanh** | Bấm vào bất kỳ dòng nào trong bảng để chuyển sang màn hình Xem và sửa chi tiết quyền lợi. |

 

 

# **19\. Chức năng thêm quyền lợi**

## **19.1. Thông tin chung**

| Mục | Nội dung |
| :---- | :---- |
| **Mã chức năng** | ADM-PRIV-02 |
| **Tên chức năng** | Thêm quyền lợi (Create Privilege) |
| **Tác nhân** | Quản trị viên (Super Admin, Admin) |
| **Mục tiêu** | Khởi tạo quyền lợi mới vào hệ thống phục vụ cấu hình ma trận gói cước. |
| **Tiền điều kiện** | Đang ở màn hình Danh mục quyền lợi (ADM-PRIV-01). |
| **Hậu điều kiện** | Quyền lợi mới lưu vào CSDL (privilege\_definitions) và tự động xuất hiện trên Ma trận quyền lợi. |

 

 

## **19.2. Luồng chính (Main Flow)**

| Bước | Tác nhân | Hành động |
| ----- | :---- | :---- |
| **1** | Quản trị viên | Bấm **"+ Thêm quyền lợi"**. |
| **2** | Hệ thống | Mở form thêm mới gồm 5 nhóm trường (các trường phụ thuộc tự động ẩn/hiện theo **Kiểu giá trị**). |
| **3** | Quản trị viên | Nhập/chọn thông tin trong 5 nhóm (A. Định danh, B. Kiểu giá trị, C. Ràng buộc giá trị, D. Đối chiếu, E. Trạng thái). |
| **4** | Quản trị viên | Bấm **"LƯU"**. |
| **5** | Hệ thống | Kiểm tra hợp lệ dữ liệu (VR) và kiểm tra trùng mã (BR). |
| **6** | Hệ thống | Lưu CSDL, ghi log kiểm toán, báo: \*"Thêm quyền lợi thành công"\*; kết thúc use case. |

 

 

## **19.3. Luồng rẽ nhánh & Ngoại lệ (Alternative Flows)**

| Mã luồng | Điều kiện | Xử lý của Hệ thống |
| :---- | :---- | :---- |
| **AF-01** | Thiếu thông tin bắt buộc / Sai định dạng | Báo lỗi ngay dưới ô nhập liệu; dừng lưu. |
| **AF-02** | Trùng Mã quyền lợi đã có | Báo lỗi: \*"Mã quyền lợi đã tồn tại"\*; dừng lưu. |
| **AF-03** | Bấm \[+\] tại Nhóm hoặc Đơn vị | Mở ô nhập nhanh → Nhập tên mới → Tự động chọn vào dropdown. |
| **AF-04** | Bấm "HỦY" | Đóng form, không lưu, quay lại danh sách. |

 

 

## **19.4. Ràng buộc dữ liệu (Validation Rules \- VR)**

### **Danh sách các trường thông tin (5 nhóm)**

| \# | Mã VR | Tên trường | Kiểu nhập | Bắt buộc | Khi nào hiện | Ý nghĩa & Quy tắc | Ví dụ |
| ----- | :---- | :---- | :---- | :---- | :---- | :---- | ----- |
| **A** |   | **ĐỊNH DANH** |   |   |   |   |   |
| 1 | **VR01** | **Mã quyền lợi** | Text (a-z, 0-9, \_) | **Có** | Luôn | Tên kỹ thuật duy nhất, không sửa sau khi lưu. | ai\_pronunciation\_quota |
| 2 | **VR02** | **Tên quyền lợi** | Text | **Có** | Luôn | Tên cho admin đọc (3 – 100 ký tự). | Hạn mức chấm phát âm AI |
| 3 | **VR03** | **Nhóm quyền lợi** | Select mở \+ \[+\] | **Có** | Luôn | Nhóm để gom trong ma trận. | AI |
| 4 | **VR04** | **Mô tả** | Textarea | Không | Luôn | Giải thích quyền lợi dùng để làm gì (\<= 255 ký tự). | Số lần AI chấm phát âm mỗi ngày |
| 5 | **VR05** | **Thứ tự hiển thị** | Number | Không | Luôn | Vị trí trong ma trận (\>= 1). | 3 |
| **B** |   | **KIỂU GIÁ TRỊ** |   |   |   |   |   |
| 6 | **VR06** | **Kiểu giá trị** | Select cố định (5) | **Có** | Luôn | Chọn 1 trong 5: • **Bật/tắt** \*(Ma trận hiện ô tích ☐/☑)\* • **Hạn mức số** \*(Ma trận hiện ô số \+ nút ∞)\* • **Dải cấp HSK** \*(Ma trận hiện 2 ô số: từ → đến)\* • **Danh mục chọn** \*(Ma trận hiện Dropdown)\* • **Bảng hạn mức** \*(Ma trận hiện Bảng: mỗi mức 1 số lần)\* | Bảng hạn mức |
| 7 | **VR07** | **Đơn vị** | Select mở \+ \[+\] | **Có** | Hạn mức số · Bảng hạn mức | Đơn vị của con số. | lượt, điểm, từ |
| 8 | **VR08** | **Chu kỳ làm mới** | Select cố định (4) | **Có** | Hạn mức số · Bảng hạn mức | Chọn: **Không làm mới** · **Hàng ngày (00:00)** · **Hàng tuần** · **Hàng tháng**. | Hàng ngày (00:00) |
| **C** |   | **RÀNG BUỘC GIÁ TRỊ** |   |   |   | \*(Khung quy định giá trị hợp lệ ở ma trận)\* |   |
| 9 | **VR09** | **Giá trị nhỏ nhất (Min)** | Number | Không | Hạn mức số · Dải cấp HSK | Biên dưới hợp lệ (\>= 0). | 0 |
| 10 | **VR10** | **Giá trị lớn nhất (Max)** | Number | Không | Hạn mức số · Dải cấp HSK | Biên trên hợp lệ (\> Min). Trống \= không chặn trần. | \*(trống)\* |
| 11 | **VR11** | **Cho phép "Không giới hạn"** | Checkbox | Không | Hạn mức số · Bảng hạn mức | Cho phép đặt vô hạn (∞) ở ma trận. | ☑ |
| 12 | **VR12** | **Danh sách giá trị** | Danh sách dòng | **Có** | Danh mục chọn · Bảng hạn mức | Các lựa chọn được phép (mỗi dòng 1 giá trị, tối thiểu 2 dòng). | Chi tiết Cơ bản |
| **D** |   | **ĐỐI CHIẾU** |   |   |   |   |   |
| 13 | **VR13** | **Đối chiếu với** | Select cố định (3) | **Có** | Luôn | Chọn 1 trong 3: 1\. **Không cần** \*(áp toàn cục, không phụ thuộc nội dung)\* 2\. **Cấp độ HSK của nội dung** \*(so hsk\_level bài với dải gói)\* 3\. **Mức phân loại của nội dung** \*(so mức Tiêu chuẩn/Nâng cao với giá trị gói)\* | Không cần |
| **E** |   | **TRẠNG THÁI** |   |   |   |   |   |
| 14 | **VR14** | **Trạng thái** | Toggle | **Có** | Luôn | Chọn: **Đang dùng** hoặc **Ngừng dùng** (Mặc định: Đang dùng). Quyền lợi ngừng dùng sẽ bị ẩn khỏi cấu hình ma trận. | Đang dùng |

 

 

### **Bảng hiện / ẩn theo Kiểu giá trị (Ô 6\)**

| Tên trường thông tin | Bật/tắt | Hạn mức số | Dải cấp HSK | Danh mục chọn | Bảng hạn mức |
| :---- | :---- | :---- | :---- | :---- | ----- |
| **7\. Đơn vị** | — | **Hiện (Bắt buộc)** | — | — | **Hiện (Bắt buộc)** |
| **8\. Chu kỳ làm mới** | — | **Hiện (Bắt buộc)** | — | — | **Hiện (Bắt buộc)** |
| **9\. Giá trị nhỏ nhất (Min)** | — | **Hiện** | **Hiện** | — | — |
| **10\. Giá trị lớn nhất (Max)** | — | **Hiện** | **Hiện** | — | — |
| **11\. Cho phép "Không giới hạn"** | — | **Hiện** | — | — | **Hiện** |
| **12\. Danh sách giá trị** | — | — | — | **Hiện (Bắt buộc)** | **Hiện (Bắt buộc)** |
| \*(8 trường còn lại: 1, 2, 3, 4, 5, 6, 13, 14)\* | **Hiện** | **Hiện** | **Hiện** | **Hiện** | **Hiện** |

 

 

## **19.5. Quy tắc nghiệp vụ (Business Rules \- BR)**

| Mã BR | Tên quy tắc | Nội dung chi tiết |
| :---- | :---- | :---- |
| **BR01** | **Bất biến Mã quyền lợi** | Mã quyền lợi là định danh kỹ thuật duy nhất trong code, không được sửa sau khi lưu. |
| **BR02** | **Form động (Dynamic Form)** | Tự động ẩn/hiện các trường Nhóm B và C ngay khi chọn Kiểu giá trị theo đúng Bảng ma trận Hiện/Ẩn. |
| **BR03** | **Thêm nhanh danh mục (\`\[+\]\`)** | Bấm \[+\] tại Nhóm quyền lợi và Đơn vị để thêm mới trực tiếp vào danh mục hệ thống. |
| **BR04** | **Sinh ô nhập trên Ma trận** | Kiểu giá trị quyết định loại ô nhập ở ma trận gói cước: Ô tích (Bật/tắt), Ô số \+ ∞ (Hạn mức số), 2 ô từ-đến (Dải HSK), Dropdown (Danh mục chọn), Bảng mức-lượt (Bảng hạn mức). |
| **BR05** | **Hiệu lực trạng thái** | • Đang dùng: Quyền lợi hiển thị trên Ma trận quyền lợi và được hệ thống kiểm tra khi học viên sử dụng tính năng tương ứng. • Ngừng dùng: Quyền lợi bị ẩn khỏi giao diện cấu hình ma trận và tạm ngừng áp dụng kiểm tra hiệu lực. |
| **BR06** | **Nhật ký kiểm toán** | Ghi nhận sự kiện tạo quyền lợi vào bảng audit\_logs (admin\_id, thời gian, chi tiết các trường cấu hình). |

 

 

# **20\. Chức năng xem và sửa chi tiết quyền lợi**

## **20.1. Thông tin chung**

| Mục | Nội dung |
| :---- | :---- |
| **Mã chức năng** | ADM-PRIV-03 |
| **Tên chức năng** | Xem và sửa chi tiết quyền lợi (View & Edit Privilege Details) |
| **Tác nhân** | Quản trị viên (Super Admin, Admin) |
| **Mục tiêu** | Tra cứu hồ sơ quyền lợi và chỉnh sửa các tham số cấu hình cho phép. |
| **Tiền điều kiện** | Đang ở màn hình Danh mục quyền lợi (ADM-PRIV-01). |
| **Hậu điều kiện** | Cập nhật CSDL, đồng bộ ma trận quyền lợi, ghi log kiểm toán. |

 

 

## **20.2. Luồng chính (Main Flow)**

| Bước | Tác nhân | Hành động |
| ----- | :---- | :---- |
| **1** | Quản trị viên | Bấm vào 1 quyền lợi trong danh sách. |
| **2** | Hệ thống | Hiển thị màn hình chi tiết (phân định rõ ô **Sửa** và ô **Chỉ đọc**). |
| **3** | Quản trị viên | Xem hoặc chỉnh sửa các trường cho phép. |
| **4** | Quản trị viên | Bấm **"LƯU"**. |
| **5** | Hệ thống | Hiển thị popup xác nhận: \*"Bạn có chắc chắn muốn lưu các thay đổi?"\*. |
| **6** | Quản trị viên | Bấm **"XÁC NHẬN"**. |
| **7** | Hệ thống | Kiểm tra hợp lệ dữ liệu (VR) và nghiệp vụ (BR). |
| **8** | Hệ thống | Cập nhật CSDL, ghi log kiểm toán, báo: \*"Cập nhật quyền lợi thành công"\*; kết thúc. |

 

 

## **20.3. Luồng rẽ nhánh & Ngoại lệ (Alternative Flows)**

| Mã luồng | Điều kiện | Xử lý của Hệ thống |
| :---- | :---- | :---- |
| **AF-01** | Chưa lưu mà bấm thoát hoặc chuyển trang | Báo popup Dirty Check: Chọn \*Rời đi\* (hủy thay đổi) hoặc \*Ở lại\*. |
| **AF-02** | Bấm "HỦY" tại popup xác nhận lưu | Đóng popup, giữ nguyên dữ liệu đang sửa trên form. |
| **AF-03** | Thiếu thông tin bắt buộc / Sai quy tắc | Báo lỗi ngay dưới ô nhập liệu; dừng lưu. |

 

 

## **20.4. Ràng buộc dữ liệu (Validation Rules \- VR)**

| \# | Mã VR | Tên trường | Chế độ | Bắt buộc | Khi nào hiện | Quy tắc chi tiết |
| ----- | :---- | :---- | :---- | :---- | :---- | ----- |
| **A** |   | **ĐỊNH DANH** |   |   |   |   |
| 1 | **VR01** | **Mã quyền lợi** | **Chỉ đọc** | — | Luôn | Khóa ô tuyệt đối (mã kỹ thuật dùng trong code, không sửa). |
| 2 | **VR02** | **Tên quyền lợi** | **Sửa** | Có | Luôn | 3 – 100 ký tự. |
| 3 | **VR03** | **Nhóm quyền lợi** | **Sửa** | Có | Luôn | Chọn dropdown hoặc bấm \[+\] thêm nhanh. |
| 4 | **VR04** | **Mô tả** | **Sửa** | Không | Luôn | Tối đa 255 ký tự. |
| 5 | **VR05** | **Thứ tự hiển thị** | **Sửa** | Không | Luôn | Số nguyên \>= 1. |
| **B** |   | **KIỂU GIÁ TRỊ** |   |   |   |   |
| 6 | **VR06** | **Kiểu giá trị** | **Chỉ đọc** | Có | Luôn | Khóa ô sau khi tạo (bảo toàn cấu trúc ma trận gói cước). |
| 7 | **VR07** | **Đơn vị** | **Sửa** | Có | Hạn mức số · Bảng hạn mức | Chọn dropdown hoặc bấm \[+\] thêm nhanh. |
| 8 | **VR08** | **Chu kỳ làm mới** | **Sửa** | Có | Hạn mức số · Bảng hạn mức | Chọn: Không làm mới · Hàng ngày · Hàng tuần · Hàng tháng. |
| **C** |   | **RÀNG BUỘC GIÁ TRỊ** |   |   |   | \*(Khung quy định giá trị hợp lệ ở ma trận)\* |
| 9 | **VR09** | **Giá trị nhỏ nhất (Min)** | **Sửa** | Không | Hạn mức số · Dải HSK | Số nguyên \>= 0. |
| 10 | **VR10** | **Giá trị lớn nhất (Max)** | **Sửa** | Không | Hạn mức số · Dải HSK | Số nguyên \> Min (để trống \= không chặn trần). |
| 11 | **VR11** | **Cho phép "Không giới hạn"** | **Sửa** | Không | Hạn mức số · Bảng hạn mức | Checkbox (cho phép đặt ∞ ở ma trận). |
| 12 | **VR12** | **Danh sách giá trị** | **Sửa** | Có | Danh mục chọn · Bảng hạn mức | Tối thiểu 2 dòng giá trị hợp lệ. |
| **D** |   | **ĐỐI CHIẾU** |   |   |   |   |
| 13 | **VR13** | **Đối chiếu với** | **Sửa** | Có | Luôn | Chọn: Không cần · Cấp độ HSK · Mức phân loại. |
| **E** |   | **TRẠNG THÁI** |   |   |   |   |
| 14 | **VR14** | **Trạng thái** | **Sửa** | Có | Luôn | Toggle: **Đang dùng** hoặc **Ngừng dùng**. |
| **F** |   | **THÔNG TIN HỆ THỐNG** |   |   |   |   |
| 15 | **VR15** | **Ngày tạo / Cập nhật** | **Chỉ đọc** | — | Luôn | Định dạng DD/MM/YYYY HH:mm:ss. |

 

 

## **20.5. Quy tắc nghiệp vụ (Business Rules \- BR)**

| Mã BR | Tên quy tắc | Nội dung chi tiết |
| :---- | :---- | :---- |
| **BR01** | **Bất biến Mã quyền lợi** | Mã quyền lợi là định danh kỹ thuật trong code, khóa chỉ đọc vĩnh viễn. |
| **BR02** | **Khóa Kiểu giá trị** | Khóa không cho đổi Kiểu giá trị sau khi tạo để bảo vệ tính nhất quán của dữ liệu ma trận. |
| **BR03** | **Dirty Check** | Cảnh báo xác nhận khi thoát trang nếu có thay đổi chưa lưu. |
| **BR04** | **Xác nhận 2 bước** | Bắt buộc có popup xác nhận trước khi lưu vào CSDL. |
| **BR05** | **Hiệu lực Trạng thái** | Ngừng dùng: Ẩn khỏi ma trận và tạm ngừng kiểm tra trên App; Đang dùng: Kích hoạt lại bình thường. |
| **BR06** | **Nhật ký kiểm toán** | Ghi log audit\_logs chi tiết: ai sửa, thời gian, giá trị cũ → giá trị mới. |

 

 

# **21\. Chức năng cấu hình ma trận quyền lợi gói cước**

## **21.1. Thông tin chung**

| Mục | Nội dung |
| :---- | :---- |
| **Mã chức năng** | ADM-PLAN-01 |
| **Tên chức năng** | Cấu hình ma trận quyền lợi gói cước (Package Entitlements Matrix) |
| **Tác nhân** | Quản trị viên (Super Admin, Admin) |
| **Mục tiêu** | Thiết lập hạn mức và tính năng mở khóa cho từng gói học tập trên bảng ma trận quyền lợi. |
| **Tiền điều kiện** | Quản trị viên đã đăng nhập vào Cổng Quản trị CMS. |
| **Hậu điều kiện** | Ma trận cấu hình mới được lưu vào CSDL và áp dụng tức thì cho học viên theo từng gói. |

 

 

## **21.2. Luồng chính (Main Flow)**

| Bước | Tác nhân | Hành động |
| ----- | :---- | :---- |
| **1** | Quản trị viên | Chọn mục **"Ma trận quyền lợi gói cước"** trên menu điều hướng. |
| **2** | Hệ thống | Hiển thị ma trận: Các cột là Gói cước (Free, Premium...); Các dòng là Quyền lợi gom theo nhóm. |
| **3** | Quản trị viên | Thiết lập giá trị trên từng ô theo đúng kiểu giá trị của quyền lợi (Bật/tắt, Nhập số/Vô hạn, Dải HSK, Danh mục, Bảng hạn mức). |
| **4** | Quản trị viên | Bấm **"LƯU CẤU HÌNH"**. |
| **5** | Hệ thống | Hiển thị popup xác nhận thay đổi ma trận. |
| **6** | Quản trị viên | Bấm **"XÁC NHẬN"**. |
| **7** | Hệ thống | Kiểm tra hợp lệ dữ liệu (VR), lưu CSDL, ghi log kiểm toán, báo: \*"Lưu ma trận quyền lợi thành công"\*; kết thúc use case. |

 

 

## **21.3. Luồng rẽ nhánh & Ngoại lệ (Alternative Flows)**

| Mã luồng | Điều kiện | Xử lý của Hệ thống |
| :---- | :---- | :---- |
| **AF-01** | Nhập số ngoài khoảng Min \- Max | Báo lỗi ngay dưới ô nhập liệu: \*"Giá trị phải nằm trong khoảng cho phép"\*; dừng lưu. |
| **AF-02** | Cấp HSK kết thúc nhỏ hơn cấp bắt đầu | Báo lỗi: \*"Cấp HSK kết thúc phải \>= cấp bắt đầu"\*; dừng lưu. |
| **AF-03** | Có thay đổi nhưng bấm thoát hoặc chuyển trang | Hiển thị popup cảnh báo dữ liệu chưa lưu (Dirty Check). |

 

 

## **21.4. Ràng buộc dữ liệu (Validation Rules \- VR)**

| Mã VR | Kiểu giá trị quyền lợi | Quy tắc kiểm tra (VR) |
| :---- | :---- | :---- |
| **VR01** | **Bật/tắt** | Chọn **Bật** (mở tính năng) hoặc **Tắt** (khóa tính năng). |
| **VR02** | **Hạn mức số** | Nhập số nguyên \>= Min và \<= Max (hoặc chọn **Vô hạn** nếu quyền lợi cho phép). |
| **VR03** | **Dải cấp HSK** | Hai số nguyên từ 1 đến 6\. Bắt buộc: **Cấp đến \>= Cấp từ** (để trống cả 2 \= Không hỗ trợ). |
| **VR04** | **Danh mục chọn** | Chọn 1 giá trị trong danh sách hợp lệ đã định nghĩa (hoặc để trống —). |
| **VR05** | **Bảng hạn mức** | Nhập số lượt cho từng mức danh mục con (hoặc chọn **Không giới hạn**). |

 

 

## **21.5. Quy tắc nghiệp vụ (Business Rules \- BR)**

| Mã BR | Tên quy tắc | Nội dung chi tiết |
| :---- | :---- | :---- |
| **BR01** | **Tự động đồng bộ từ Danh mục quyền lợi** | Quyền lợi mới tạo ở chức năng Thêm quyền lợi (ADM-PRIV-02) sẽ tự động xuất hiện thành 1 dòng mới trong nhóm tương ứng trên ma trận. |
| **BR02** | **Nguyên tắc phân cấp quyền lợi gói** | Quyền lợi của gói **Premium** phải luôn cao hơn hoặc bằng gói **Free**. |
| **BR03** | **Hiệu lực tức thì** | Cấu hình ma trận sau khi lưu áp dụng ngay lập tức cho các lượt gọi API kiểm tra quyền tiếp theo từ Mobile App/Web. |
| **BR04** | **Nhật ký kiểm toán** | Ghi nhận chi tiết lịch sử mọi ô bị thay đổi (tên gói, tên quyền lợi, giá trị cũ → giá trị mới, admin\_id, thời gian). |

 

