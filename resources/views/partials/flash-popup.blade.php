@if (session()->has('success') || session()->has('error') || session()->has('info'))
    {{-- SweetAlert2 --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
    document.addEventListener('DOMContentLoaded', () => {
      const data = {
        success: @json(session('success')),
        error:   @json(session('error')),
        info:    @json(session('info')),
        title:   @json(session('flash_title')),      // ตั้งหัวข้อเองได้ (ไม่ใส่ก็ใช้ค่า default)
        goCart:  @json(session('go_cart', false)),   // true = ปุ่มหลัก "ไปตะกร้า"

        // 👇 ของใหม่ เพื่อเปลี่ยนปุ่มรองตามบริบท
        context:      @json(session('flash_context')),         // 'booking' | 'cart' | ...
        cancelText:   @json(session('continue_label')),        // override label ปุ่มรอง (ไม่บังคับ)
        continueUrl:  @json(session('continue_url')),          // url ที่ให้ไปเมื่อกดปุ่มรอง (ไม่บังคับ)
      };

      const type  = data.success ? 'success' : (data.error ? 'error' : 'info');
      const text  = data.success ?? data.error ?? data.info ?? '';
      const title = data.title || (
        type === 'success'
          ? @json(__('messages.success'))
          : (type === 'error'
              ? @json(__('messages.error'))
              : @json(__('messages.notice')))
      );

      // ปุ่มรอง: ถ้า context = 'booking' ให้ใช้ "เลือกจองคอร์สต่อ" แทน "เลือกสินค้าต่อ"
      const defaultCancel = (data.context === 'booking')
          ? @json(__('messages.continue_booking'))
          : @json(__('messages.continue_shopping'));
      const cancelBtn = data.cancelText || defaultCancel;

      Swal.fire({
        icon: type,
        title: title,
        text: text,
        showCancelButton: true,  // ให้มีปุ่มรองเสมอ
        confirmButtonText: data.goCart
            ? @json(__('messages.go_to_cart'))
            : @json(__('messages.ok')),
        cancelButtonText: cancelBtn,
        confirmButtonColor: '#2e7d32',
        showCloseButton: true,
        timer: data.goCart ? undefined : 2500,
        timerProgressBar: data.goCart ? false : true
      }).then(res => {
        // ปุ่มหลัก
        if (res.isConfirmed && data.goCart) {
          window.location.href = @json(route('member.cart'));
        }
        // ปุ่มรอง (เช่น เลือกจองคอร์สต่อ)
        if (res.dismiss === Swal.DismissReason.cancel && data.continueUrl) {
          window.location.href = data.continueUrl;
        }
      });
    });
    </script>
@endif
