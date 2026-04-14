<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Page;
use Illuminate\Support\Str;

class PolicyPagesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $policies = [
            [
                'title' => 'Chính sách bảo mật thông tin',
                'slug' => 'chinh-sach-bao-mat-thong-tin',
                'content' => '<h2>1. Mục đích và phạm vi thu thập thông tin</h2>
<p>Dữ liệu thu thập trên website CNETPos chủ yếu bao gồm: Họ tên, email, số điện thoại, tên doanh nghiệp và địa chỉ. CNETPos sử dụng những thông tin này để:</p>
<ul>
<li>Hỗ trợ khách hàng trong quá trình tìm hiểu, mua và sử dụng hệ thống phần mềm CNETPos.</li>
<li>Giải đáp thắc mắc, cung cấp tài khoản dùng thử và hỗ trợ kỹ thuật.</li>
<li>Gửi thông báo về các tính năng mới, bản cập nhật hoặc các chương trình khuyến mãi.</li>
</ul>

<h2>2. Phạm vi sử dụng thông tin</h2>
<p>CNETPos cam kết chỉ sử dụng thông tin cá nhân của khách hàng trong nội bộ công ty. Chúng tôi tuyệt đối <strong>KHÔNG</strong> mua bán, trao đổi hay chia sẻ thông tin khách hàng cho bất kỳ bên thứ ba nào khác vì mục đích thương mại, ngoại trừ các trường hợp có yêu cầu từ cơ quan pháp luật có thẩm quyền.</p>

<h2>3. Thời gian lưu trữ thông tin</h2>
<p>Dữ liệu cá nhân của Thành viên sẽ được lưu trữ bảo mật trên máy chủ của CNETPos cho đến khi có yêu cầu hủy bỏ tư cách thành viên từ chính quý khách hàng qua hệ thống chăm sóc khách hàng.</p>

<h2>4. Địa chỉ của đơn vị thu thập và quản lý thông tin cá nhân</h2>
<p><strong>CÔNG TY TNHH GIẢI PHÁP PHẦN MỀM CNET</strong></p>
<p>Hệ thống hỗ trợ tiếp nhận qua Email: hotro@cnetpos.vn hoặc Hotline hiển thị trên website.</p>

<h2>5. Phương tiện và công cụ để người dùng tiếp cận và chỉnh sửa dữ liệu</h2>
<p>Khách hàng có quyền tự kiểm tra, cập nhật, điều chỉnh hoặc hủy bỏ thông tin cá nhân của mình bằng cách đăng nhập vào tài khoản trên phần mềm CNETPos, hoặc yêu cầu ban quản trị website CNETPos thực hiện việc này.</p>
',
            ],
            [
                'title' => 'Quy định & Điều khoản dịch vụ',
                'slug' => 'dieu-khoan-dich-vu',
                'content' => '<h2>1. Chấp nhận Điều khoản</h2>
<p>Chào mừng bạn đến với CNETPos. Khi bạn truy cập website hoặc đăng ký tài khoản sử dụng phần mềm quản lý (Cloud POS/ERP) của chúng tôi, bạn mặc nhiên đã đồng ý với các Quy định & Điều khoản dịch vụ này. Vui lòng đọc kỹ trước khi sử dụng.</p>

<h2>2. Cấp phép và Giới hạn sử dụng</h2>
<p>Hệ thống phần mềm CNETPos được cung cấp theo hình thức Dịch vụ (SaaS - Software as a Service). Bạn được cấp quyền sử dụng phần mềm trong thời gian gói cước có hiệu lực.</p>
<ul>
<li>Bạn không được quyền sao chép, chỉnh sửa, giải mã hoặc bán lại mã nguồn của hệ thống.</li>
<li>Bạn hoàn toàn chịu trách nhiệm về tính hợp pháp của các dữ liệu và nội dung công việc kinh doanh mà bạn nhập vào hệ thống CNETPos.</li>
</ul>

<h2>3. Quyền sở hữu trí tuệ</h2>
<p>Mọi bản quyền phần mềm, thương hiệu, mã nguồn, UI/UX trên hệ thống đều thuộc quyền sở hữu của CNETPos. Việc sử dụng trái phép có thể bị can thiệp bởi pháp luật.</p>

<h2>4. Tạm ngưng và Chấm dứt dịch vụ</h2>
<p>Chúng tôi có quyền tạm ngưng cung cấp dịch vụ nếu phát hiện tài khoản của bạn có dấu hiệu gian lận, vi phạm pháp luật Việt Nam, hoặc bạn chậm trễ trong việc gia hạn thanh toán cước phí theo thỏa thuận hợp đồng.</p>',
            ],
            [
                'title' => 'Chính sách vận chuyển & Giao nhận',
                'slug' => 'chinh-sach-van-chuyen',
                'content' => '<h2>1. Giao nhận Dịch vụ Phần mềm (Sản phẩm số)</h2>
<p>CNETPos chuyên cung cấp các giải pháp phần mềm quản lý dạng Cloud ERP/POS. Đối với sản phẩm này, chúng tôi <strong>không vận chuyển sản phẩm vật lý</strong> (như CD, USB, hộp phần mềm).</p>
<ul>
<li><strong>Cách thức giao nhận:</strong> Toàn bộ thông tin tài khoản đăng nhập (Username/Password), tên miền riêng (Sub-domain) và tài liệu hướng dẫn sử dụng sẽ được gửi trực tiếp vào <strong>Địa chỉ Email</strong> hoặc <strong>Zalo</strong> mà quý khách đã cung cấp lúc đăng ký.</li>
<li><strong>Thời gian giao nhận:</strong> Ngay sau khi ký hợp đồng dịch vụ hoặc hệ thống xác nhận thanh toán thành công (thời gian cấu hình tối đa 01 - 04 giờ làm việc).</li>
</ul>

<h2>2. Giao nhận Thiết bị Phần cứng (Nếu có)</h2>
<p>Trong trường hợp quý khách mua thêm các thiết bị phần cứng hỗ trợ bán hàng (Máy in hóa đơn, máy quét mã vạch, ngăn kéo đựng tiền...):</p>
<ul>
<li>Chúng tôi áp dụng hình thức vận chuyển thông qua các đơn vị chuyển phát uy tín (Viettel Post, VNPost, GHTK...) hoặc nhân viên kỹ thuật giao và lắp đặt trực tiếp.</li>
<li><strong>Chi phí và thời gian:</strong> Tùy thuộc vào vị trí địa lý của khách hàng, phí vận chuyển có thể được miễn phí hoặc tính phí thỏa thuận. Thời gian nhận hàng vật lý từ 1-5 ngày làm việc.</li>
</ul>',
            ],
            [
                'title' => 'Chính sách đổi trả & Hoàn tiền',
                'slug' => 'chinh-sach-doi-tra',
                'content' => '<h2>1. Chính sách trải nghiệm (Dùng thử)</h2>
<p>Để đảm bảo sự an tâm tuyệt đối, CNETPos luôn cung cấp tài khoản dùng thử (Demo) và thời gian dùng thử hệ thống từ 07 đến 14 ngày hoàn toàn miễn phí. Khách hàng có quyền trải nghiệm toàn bộ tính năng trước khi quyết định ký hợp đồng và thanh toán dịch vụ.</p>

<h2>2. Điều kiện áp dụng hoàn tiền</h2>
<p>Với bản chất là dịch vụ phần mềm lưu trữ trên Cloud đã được dùng thử trước khi mua, chúng tôi <strong>KHÔNG</strong> áp dụng chính sách đổi trả/hoàn tiền đối với các khoản phí thuê bao phần mềm đã được thanh toán, ngoại trừ các trường hợp bất khả kháng sau:</p>
<ul>
<li>Phần mềm xảy ra lỗi kỹ thuật nghiêm trọng liên tục từ phía máy chủ CNETPos khiến cửa hàng không thể hoạt động được trong thời gian dài, và đội ngũ kỹ thuật không thể khắc phục được sự cố.</li>
<li>Thanh toán bị trùng lặp do lỗi hệ thống cổng thanh toán. Trong trường hợp này, khoản tiền dư sẽ được hoàn lại 100% trong vòng 3-5 ngày làm việc.</li>
</ul>

<h2>3. Đối với Thiết bị phần cứng</h2>
<p>Nếu thiết bị phần cứng (máy in, máy quét) do CNETPos cung cấp bị lỗi do nhà sản xuất trong vòng 07 ngày đầu, chúng tôi hỗ trợ <strong>1 đổi 1</strong> miễn phí. Sản phẩm đổi trả phải còn nguyên tem bảo hành, không bị rơi vỡ hay ngấm nước.</p>',
            ],
            [
                'title' => 'Chính sách thanh toán',
                'slug' => 'chinh-sach-thanh-toan',
                'content' => '<h2>1. Các hình thức thanh toán</h2>
<p>Để mang đến sự tiện lợi khi mua gói dịch vụ phần mềm hoặc gia hạn, CNETPos cung cấp các hình thức thanh toán sau:</p>
<ul>
<li><strong>Chuyển khoản ngân hàng:</strong> Đây là phương thức phổ biến nhất. Khách hàng có thể chuyển khoản số tiền tương ứng gói cước tới số tài khoản công ty được ghi chú trên Hợp đồng hoặc Hóa đơn điện tử.</li>
<li><strong>Thanh toán trực tuyến:</strong> Thanh toán qua QR Code, ví điện tử hoặc cổng thanh toán tích hợp trực tiếp trên hệ thống quản lý tài khoản của CNETPos.</li>
<li><strong>Thanh toán Tiền mặt:</strong> Áp dụng khi nhân viên CNETPos đến hướng dẫn và triển khai trực tiếp tại cửa hàng của quý khách.</li>
</ul>

<h2>2. Quy định thanh toán & Gia hạn</h2>
<ul>
<li>Cước phí dịch vụ phần mềm là phí trả trước (Pre-paid) theo chu kỳ (1/3/6 tháng hoặc 1 năm). Quý khách vui lòng thanh toán để duy trì hoặc khởi tạo dịch vụ.</li>
<li>Hệ thống sẽ tự động gửi thông báo nhắc nhở gia hạn qua Email/Phần mềm trước 7-15 ngày khi gói cước sắp hết hạn.</li>
<li>CNETPos bảo lưu quyền tạm ngưng hệ thống nếu khách hàng chậm chễ thanh toán gia hạn quá số ngày quy định mà không có thông báo thỏa thuận.</li>
</ul>'
            ]
        ];

        foreach ($policies as $policyData) {
            $slug = $policyData['slug'];
            unset($policyData['slug']);

            // Insert into pages table based on title -> update content
            $page = Page::updateOrCreate(
                ['title' => $policyData['title']],
                [
                    'content' => $policyData['content'],
                    'status' => true,
                ]
            );

            // Manual slug injection compatible with HasSlug logic
            $page->slugData()->updateOrCreate(
                [],
                ['slug' => $slug]
            );
        }
    }
}
