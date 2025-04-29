from selenium import webdriver
from selenium.webdriver.chrome.options import Options
from selenium.webdriver.common.by import By
from selenium.webdriver.support.ui import WebDriverWait
from selenium.webdriver.support import expected_conditions as EC

def login(driver, wait, username, password):
    driver.get("http://localhost/analysis_project/login.php")

    # 輸入帳密
    wait.until(EC.presence_of_element_located((By.NAME, "user_id"))).send_keys(username)
    driver.find_element(By.NAME, "password").send_keys(password)

    # 點擊登入按鈕
    driver.find_element(By.XPATH, "//button[text()='登入'] | //input[@type='submit']").click()

    # 確認登入成功
    wait.until(EC.presence_of_element_located((By.XPATH, f"//a[contains(text(), '{username}')]")))
    assert "homepage.php" in driver.current_url, "登入失敗，未跳轉至 homepage.php"
    print("✅ 登入成功，已跳轉至 homepage.php")

def test_submit_advice():
    options = Options()
    options.add_experimental_option("detach", True)
    driver = webdriver.Chrome(options=options)
    wait = WebDriverWait(driver, 10)

    # 登入
    login(driver, wait, "1111", "1111")  # 替換你的帳密

    # 回到首頁
    driver.get("http://localhost/analysis_project/homepage.php")

   # 點擊「建言」下拉（使用 JavaScript 觸發）
    dropdown_button = driver.find_element(By.XPATH, "//button[text()='建言']")
    driver.execute_script("arguments[0].click();", dropdown_button)

    # 等待「提交建言」並點擊
    wait.until(EC.element_to_be_clickable((By.XPATH, "//a[contains(text(), '提交建言')]"))).click()



    # 驗證跳轉
    assert "submitadvice.php" in driver.current_url
    print("✅ 成功點擊提交建言")



    # 輸入標題
    wait.until(EC.presence_of_element_located((By.ID, "title"))).send_keys("自動測試建言")

    # 點擊分類按鈕（例如：設施改善）
    driver.find_element(By.XPATH, "//button[@data-value='equipment']").click()

    # 輸入內容
    driver.find_element(By.ID, "content").send_keys("這是由 Selenium 自動填寫的建言內容。")

    # 上傳圖片（確保 test_image.jpg 存在）
    photo_input = driver.find_element(By.ID, "photoFileInput")
    photo_input.send_keys("C:/Users/User/Downloads/chicken.png")  # ← 替換成正確絕對路徑

    # 上傳文件（確保 test_doc.pdf 存在）
    file_input = driver.find_element(By.ID, "fileFileInput")
    file_input.send_keys("C:/Users/User/Downloads/輔仁114輔系公告.pdf")  # ← 替換成正確絕對路徑

    # 提交表單
    submit_button = driver.find_element(By.CLASS_NAME, "submit")
    driver.execute_script("arguments[0].click();", submit_button)

    # 驗證是否成功（可根據你導向的頁面修改）
    WebDriverWait(driver, 10).until(EC.url_contains("homepage.php"))
    print("✅ 表單提交成功")



if __name__ == "__main__":
    test_submit_advice()
