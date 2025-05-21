from selenium import webdriver
from selenium.webdriver.common.by import By
from selenium.webdriver.common.keys import Keys
from selenium.webdriver.common.action_chains import ActionChains
from selenium.webdriver.support.ui import WebDriverWait
from selenium.webdriver.support import expected_conditions as EC
import time

# 配置 Chrome Driver
driver = webdriver.Chrome()

# 開啟首頁
driver.get('http://localhost/analysis_project/advice_search.php')

# 等待頁面加載完成
wait = WebDriverWait(driver, 10)
wait.until(EC.presence_of_element_located((By.ID, 'category')))  # 等待分類選單出現

# 測試分類下拉選單功能
category_select = driver.find_element(By.ID, 'category')
category_select.click()  # 點擊下拉選單
time.sleep(1)  # 等待選單顯示
category_select.send_keys(Keys.DOWN)  # 模擬鍵盤向下按鍵選擇 "學術發展"
category_select.send_keys(Keys.RETURN)  # 確認選擇

# 測試搜索欄功能
search_input = driver.find_element(By.ID, 'search')
search_input.clear()  # 清除之前的輸入
search_input.send_keys("廁所")  # 輸入搜索關鍵字
search_input.send_keys(Keys.RETURN)  # 按下 Enter 鍵

# 測試排序選單功能
sort_btn = driver.find_element(By.ID, 'sortBtn')
sort_btn.click()  # 點擊排序按鈕
print("成功點擊排序選單")

# 等待排序選單出現並選擇 "最熱門"
sort_menu = driver.find_element(By.ID, 'sortMenu')
wait.until(EC.visibility_of(sort_menu))
sort_option = driver.find_element(By.XPATH, "//div[text()='最熱門']")
sort_option.click()
print("成功選擇排序選項:最熱門")

# 測試建議項目顯示
wait.until(EC.presence_of_element_located((By.ID, 'suggestion-list')))  # 等待建議項目加載
suggestion_list = driver.find_element(By.ID, 'suggestion-list')

# 檢查是否有建議項目顯示
assert suggestion_list.is_displayed(), "建議項目未顯示"

# 測試點擊建議項目
first_suggestion = suggestion_list.find_element(By.CLASS_NAME, "suggestion")
first_suggestion.click()  # 點擊第一個建議項目

# 等待頁面跳轉並檢查是否正確跳轉
wait.until(EC.url_contains("advice_detail.php"))
assert "advice_detail.php" in driver.current_url, "未跳轉到建議詳細頁面"

# 關閉瀏覽器
driver.quit()
