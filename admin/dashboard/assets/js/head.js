// Lấy element
const emojiElement = document.querySelector(".emoji");
const messageElement = document.querySelector(".message");

// Danh sách emoji và message ngẫu nhiên
const emojiList = [
  "( ͡° ͜ʖ ͡°)",
  "¯\\_(ツ)_/¯",
  "(╯°□°）╯︵ ┻━┻",
  "(⌐■_■)",
  "(¬‿¬)",
  "ʕ·ᴥ·ʔ",
  "(☞ﾟヮﾟ)☞",
  "(≧◡≦)",
  "(„• ֊ •„)",
  "( ˘ ³˘)♥",
  "(╯°□°）╯︵┻━┻", // Bàn bay
  "(⊙_⊙)", // Sốc
  "(⌐■_■)", // Cool ngầu
  "(¬‿¬)", // Troll
  "(X_X)", // Chết
  "( ˘ ³˘)~♥", // Thơm
  "(c[_]c)", // Cà phê (ASCII art)
  "(@_@)", // Mắt giật
  "(^_^)/~☆", // Vé vẫy tay
  "(¬‿¬)━☆ﾟ.*", // VIP phép thuật
];

const messageList = [
  "Hôm nay đẹp trời… nhưng Admin vẫn FA!",
  "Alert! Phát hiện admin đang lướt Facebook",
  "Warning! Nhiệt độ CPU vượt ngưỡng FA",
  "Chế độ God Mode: Activated",
  "Bạn có 1 thông báo mới... từ 3 tiếng trước",
  "Hệ thống đề xuất: Đi uống trà đá đi ạ!",
  "5 task tồn - 1 tách cafe - 0 động lực",
  "Chế độ lười kinh niên: Activated",
  "WARNING! Đừng ấn nút đỏ! (Thực ra không có nút đỏ)",
  "Hack não thành công - Giờ bạn là coder FA",
  "ALERT! Phát hiện Admin đang Google cách thoát FA",
  "Critical Hit! Tỉ lệ hoàn thành task: 0.01%",
  "Unlock Achievement: 'Cú đêm huyền thoại'",
  "Nhiệm vụ bí mật: Tán tỉnh đồng nghiệp qua Slack",
  "WARNING! Bạn vừa mở tab ẩn... hệ thống đã chụp màn hình",
  "Chế độ 'Sến súa' kích hoạt: Gửi crush 1 tin nhắn nào!",
  "Lượng caffeine trong máu: 500%",
  "Hệ thống phát hiện... mắt trái đang giật điềm báo deadline!",
  "Phần thưởng: 1 vé xem 'Hội FA đấu với Deadline'",
  "Gợi ý: Thử hét 'I’m rich' để mở khóa tính năng VIP",
];

// Hàm random index
function getRandomIndex(arr) {
  return Math.floor(Math.random() * arr.length);
}

// Hàm cập nhật nội dung
function updateContent() {
  const randomIndex = getRandomIndex(emojiList);

  emojiElement.textContent = emojiList[randomIndex];
  messageElement.textContent = messageList[randomIndex];
}

// Chạy lần đầu ngay lập tức
updateContent();

// Cập nhật mỗi 10 giây
setInterval(updateContent, 10000);
