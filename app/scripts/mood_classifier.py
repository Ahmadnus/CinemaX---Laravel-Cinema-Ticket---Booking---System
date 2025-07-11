import sys
import json

def suggest_genres(message: str):
    # البيانات مخزنة داخل الكود
    data = [
         {"mood": "happy", "keywords": ["happy", "joyful", "glad"], "recommended_genre": "comedy"},
    {"mood": "sad", "keywords": ["sad", "depressed", "unhappy"], "recommended_genre": "drama"},
    {"mood": "excited", "keywords": ["excited", "energetic", "pumped"], "recommended_genre": "action"},
    {"mood": "romantic", "keywords": ["romantic", "love", "affectionate"], "recommended_genre": "romance"},
    {"mood": "bored", "keywords": ["bored", "dull", "uninterested"], "recommended_genre": "sci-fi"},
    {"mood": "scared", "keywords": ["scared", "afraid", "nervous"], "recommended_genre": "horror"},
    ]

    message = message.lower()
    matched_genres = []

    for entry in data:
        for kw in entry["keywords"]:
            if kw in message:
                matched_genres.append({
                    "mood": entry["mood"],
                    "recommended_genre": entry["recommended_genre"]
                })
                break  # عدم التكرار

    if matched_genres:
        print(json.dumps(matched_genres, ensure_ascii=False))
    else:
        print(json.dumps([{"mood": "neutral", "recommended_genre": "comedy"}], ensure_ascii=False))


if __name__ == "__main__":
    if len(sys.argv) > 1:
        suggest_genres(sys.argv[1])
    else:
        print(json.dumps([{"mood": "neutral", "recommended_genre": "comedy"}], ensure_ascii=False))
