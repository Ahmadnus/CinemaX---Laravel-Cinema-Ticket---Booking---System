import pandas as pd

def print_csv_contents():
    try:
        df = pd.read_csv("mood_to_genre.csv")  # تأكد إن الملف بنفس الفولدر
        print("CSV content:")
        print(df.to_string(index=False))
    except FileNotFoundError:
        print("Error: الملف mood_to_genre.csv غير موجود في المسار الحالي.")
    except Exception as e:
        print(f"Error: حدث خطأ غير متوقع: {e}")

if __name__ == "__main__":
    print_csv_contents()