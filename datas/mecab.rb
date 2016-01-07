# 今回使うライブラリを読んでおく。最低限必須なのはnattoだけ。
require 'natto'
require 'pp'
require 'csv'

# こちらの配列に修造の名言を品詞分解したものを放り込んでいく。
syuzo_words = []

# csvファイルを読み込む。1行ずつ処理。
CSV.open('title2.csv', 'r') do |reader|

    # Nattoを介してMecabエンジンを起動！
    nm = Natto::MeCab.new

    # 読み込んだcsvファイルの各行を順次処理する。
    reader.each do |row|

        # ここでnatto_mecabを介してパース!(解析。ここでは品詞分解。)
        # 分解されて出てきた品詞をnという変数に放り込む
        nm.parse(row[0]) do |n|
            # nのsurfaceには品詞そのものが詰まっているのでそれをいただく。
            # 他にn.feaureで細かい属性情報(品詞の種類やよみがななど)を取得できます。
            part = n.feature[0]+n.feature[1]
            # pp part
            if part == "名詞" then
                s = n.surface ? n.surface : "-"
                syuzo_words << s
            end
        end
    end
end
# ここまででこんなかんじ。
# syuzo_words = ["ナイス", "ボレー", "、", "修造", "！", .....]


words_and_count = []

# 品詞分解した結果を集計
# #uniqで重複をなくしつつ、
# #map処理で各要素ごとに品詞分解した中身(word)と、
# それが何回カウントされているか(syuzo_words.grep(word).count)を作り出している。
syuzo_words.uniq.map do |word|
    words_and_count[words_and_count.size] = ["#{word}", "#{syuzo_words.grep(word).count}"] if word
end
# ここまででこんな感じ
# words_and_count = [["ナイス", "2"], ["ボレー", "1"], ["、", "12"], ["修造", "3"], ["！", "23"],...]


# 最後にsort_byで並び替えて更に降順に。
# word_and_count[1]とは["ナイス", "2"]のindex1つまり、"2"の値を指しており、その値を元に並び替え。
pp words_and_count.sort_by { |word_and_count| word_and_count[1].to_i }.reverse
data = words_and_count.sort_by { |word_and_count| word_and_count[1].to_i }.reverse
pp data.length

fileurl = 'mecabout2.json'
datas = "["
for i in 0..data.length-1 do
    datas += data[i].to_s.gsub('[','{').gsub(']','}').gsub(',',':').gsub(' ','')
    if i < (data.length - 1) then
        datas += ","
    end
end
File.write(fileurl, datas + "]")
