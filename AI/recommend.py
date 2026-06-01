import sys
import json
import mysql.connector
import pandas as pd
from sklearn.neighbors import NearestNeighbors
import warnings
warnings.filterwarnings("ignore")
def new_func():
    return 3307

db_config = {
    "host": "127.0.0.1",
    "user": "root",
    "password": "",
    "database": "agriconnect",
    "port": new_func()
}

def recommend(user_id):

    conn = mysql.connector.connect(**db_config)

    # 1️⃣ Load user-product data
    query = """
        SELECT user_id, product_id
        FROM buy_requests
        WHERE status='accepted'
    """
    df = pd.read_sql(query, conn)

    # If no data
    if df.empty:
        return {"products": [], "reason": "No data available"}

    # 2️⃣ Create matrix
    matrix = pd.crosstab(df['user_id'], df['product_id'])

    # 3️⃣ If new user → top selling
    if user_id not in matrix.index:
        top_products = df['product_id'].value_counts().head(4).index.tolist()
        return {
            "products": top_products,
            "reason": "Top selling products (new user)"
        }

    # 4️⃣ Train ML model (KNN)
    model = NearestNeighbors(metric='cosine', algorithm='brute')
    model.fit(matrix)

    user_vector = matrix.loc[user_id].values.reshape(1, -1)

    distances, indices = model.kneighbors(user_vector, n_neighbors=5)

    similar_users = matrix.index[indices.flatten()].tolist()

    # 5️⃣ Collect recommended products
    recommended = set()

    for u in similar_users:
        if u != user_id:
            recommended |= set(matrix.loc[u][matrix.loc[u] > 0].index)

    # 6️⃣ Remove already bought
    user_products = set(matrix.loc[user_id][matrix.loc[user_id] > 0].index)
    recommended = recommended - user_products

    result = list(recommended)[:4]

    # 7️⃣ Fallback if less results
    if len(result) < 4:
        top_products = df['product_id'].value_counts().index.tolist()
        result = list(set(result + top_products))[:4]

    conn.close()

    return {
        "products": result,
        "reason": "Recommended based on similar users (ML - KNN)"
    }


if __name__ == "__main__":
    user_id = int(sys.argv[1])
    output = recommend(user_id)
    print(json.dumps(output))