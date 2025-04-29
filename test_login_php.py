from selenium import webdriver
from selenium.webdriver.common.by import By
from selenium.webdriver.support.ui import WebDriverWait
from selenium.webdriver.support import expected_conditions as EC
from selenium.common.exceptions import TimeoutException, NoSuchElementException
from selenium.webdriver.chrome.service import Service
from selenium.webdriver.chrome.options import Options

# 初始化瀏覽器（設定為全域變數）
def setup_driver():
    chrome_options = Options()
    chrome_options.add_argument("--start-maximized")
    service = Service()
    driver = webdriver.Chrome(service=service, options=chrome_options)
    return driver

driver = setup_driver()
wait = WebDriverWait(driver, 10)

def login():
    """執行登入流程"""
    driver.get("http://localhost/analysis_project/login.php")
    try:
        wait.until(EC.presence_of_element_located((By.NAME, "user_id"))).send_keys("1111")
        driver.find_element(By.NAME, "password").send_keys("1111")
        driver.find_element(By.CLASS_NAME, "sign-in-btn").click()
        wait.until(EC.url_contains("homepage.php"))
        print("✅ 登入成功，已進入 homepage.php")
    except TimeoutException:
        print("❌ 登入流程失敗，找不到登入欄位或無法跳轉。")

def click_navbar_button(button_text):
    """點擊 navbar 按鈕"""
    try:
        btn = wait.until(EC.element_to_be_clickable((By.XPATH, f"//button[contains(text(), '{button_text}')]")))
        btn.click()
        print(f"✅ 成功點擊 navbar 按鈕：{button_text}")
    except TimeoutException:
        print(f"❌ 找不到 navbar 按鈕：{button_text}")

def click_link_and_check(link_text, expected_url_part):
    """點擊連結並檢查網址"""
    try:
        link = wait.until(EC.element_to_be_clickable((By.LINK_TEXT, link_text)))
        link.click()
        wait.until(EC.url_contains(expected_url_part))
        print(f"✅ 成功點擊 '{link_text}' 並跳轉到包含 '{expected_url_part}' 的頁面")
    except TimeoutException:
        print(f"❌ 點擊 '{link_text}' 或跳轉 '{expected_url_part}' 失敗")

def fill_advice_form():
    """填寫建言表單並提交"""
    try:
        wait.until(EC.presence_of_element_located((By.ID, "title"))).send_keys("改善學校圖書館設施")
        print("✅ 標題已填寫")

        category_btn = driver.find_element(By.XPATH, "//button[@data-value='equipment']")
        category_btn.click()
        wait.until(EC.presence_of_element_located((By.ID, "selected-category"))).send_keys("equipment")
        print("✅ 分類已選擇")

        driver.find_element(By.ID, "content").send_keys("希望改善圖書館的閱讀區域，提供更多座位。")
        print("✅ 內容已填寫")

        driver.find_element(By.CLASS_NAME, "submit").click()
        wait.until(EC.url_contains("advice_accept.php"))
        print("✅ 表單已提交並跳轉至 'advice_accept.php'")
    except (TimeoutException, NoSuchElementException):
        print("❌ 填寫或提交表單失敗")

def test_navbar_links():
    """測試 navbar 中的所有連結"""
    # 測試建言功能
    click_navbar_button('建言')
    click_link_and_check("建言瀏覽", "advice_search.php")
    driver.back()

    click_navbar_button('建言')
    if "提出建言" in driver.page_source:
        click_link_and_check("提出建言", "submitadvice.php")
        fill_advice_form()
        driver.back()

    # 測試募資功能
    click_navbar_button('募資')
    click_link_and_check("進行中募資", "ongoing_funding_search.php")
    driver.back()

    click_navbar_button('募資')
    click_link_and_check("已結束募資", "due_funding_search.php")
    driver.back()

def logout():
    """登出流程"""
    try:
        logout_link = wait.until(EC.element_to_be_clickable((By.LINK_TEXT, "登出")))
        logout_link.click()
        wait.until(EC.url_contains("login.php"))
        print("✅ 成功登出並回到登入頁面")
    except TimeoutException:
        print("❌ 登出失敗")

def main():
    """主流程"""
    try:
        login()
        test_navbar_links()
        logout()
    finally:
        driver.quit()
        print("✅ 測試結束，瀏覽器已關閉")

if __name__ == "__main__":
    main()
