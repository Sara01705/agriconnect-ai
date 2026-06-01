import sys
import json
import os
from sklearn.feature_extraction.text import TfidfVectorizer
from sklearn.naive_bayes import MultinomialNB

# user message
message = sys.argv[1].lower()

# load intents safely
base_dir = os.path.dirname(__file__)
intents_path = os.path.join(base_dir, "intents.json")

with open(intents_path) as file:
    data = json.load(file)

sentences = []
labels = []

# prepare training data
for intent in data['intents']:
    for pattern in intent['patterns']:
        sentences.append(pattern)
        labels.append(intent['tag'])

# vectorize text
vectorizer = TfidfVectorizer(stop_words='english')
X = vectorizer.fit_transform(sentences)

# train model
model = MultinomialNB()
model.fit(X, labels)

# predict intent
msg_vector = vectorizer.transform([message])
intent = model.predict(msg_vector)[0]

# return JSON
print(json.dumps({"intent": intent}))