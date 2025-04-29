from selenium import webdriver
from selenium.webdriver.common.by import By
from selenium.webdriver.common.keys import Keys
from selenium.webdriver.chrome.service import Service
from selenium.webdriver.chrome.options import Options
from selenium.webdriver.support.ui import WebDriverWait
from selenium.webdriver.support import expected_conditions as EC
from selenium.common.exceptions import TimeoutException

# 初始化瀏覽器設定
def setup_driver():
    chrome_options = Options()
    chrome_options.add_argument("--start-maximized")  # 最大化窗口
    service = Service()
    driver = webdriver.Chrome(service=service, options=chrome_options)
    return driver

# 初始化 WebDriver 和 WebDriverWait
driver = setup_driver()
wait = WebDriverWait(driver, 10)

def test_login():
    """測試登入功能"""
    driver.get("http://localhost/analysis_project/login.php")

    try:
        # 等待並輸入學號
        user_id_field = wait.until(EC.presence_of_element_located((By.NAME, "user_id")))
        user_id_field.send_keys("1111")  # 輸入學號

        # 輸入密碼
        password_field = driver.find_element(By.NAME, "password")
        password_field.send_keys("1111")  # 輸入密碼

        # 提交表單（點擊登入按鈕）
        sign_in_button = driver.find_element(By.CLASS_NAME, "sign-in-btn")
        sign_in_button.click()

        # 等待頁面跳轉至 homepage.php
        wait.until(EC.url_contains("homepage.php"))

        # 驗證登入是否成功，檢查 URL 是否包含 homepage.php
        assert "homepage.php" in driver.current_url
        print("✅ 登入成功，已跳轉至 homepage.php")

    except TimeoutException:
        print("❌ 登入流程超時，無法完成登入或無法跳轉。")
    except AssertionError:
        print("❌ 登入失敗，未跳轉至 homepage.php。")
    finally:
        driver.quit()

# 執行測試
test_login()
