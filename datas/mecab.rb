# 今回使うライブラリを読んでおく。最低限必須なのはnattoだけ。
require 'natto'
require 'pp'
require 'csv'

# こちらの配列に修造の名言を品詞分解したものを放り込んでいく。
words = []

# csvファイルを読み込む。1行ずつ処理。
CSV.open('title.csv', 'r') do |reader|

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
                words << s
            end
        end
    end
end
# ここまででこんなかんじ。
# syuzo_words = ["ナイス", "ボレー", "、", "修造", "！", .....]
pp words
File.write("./words.csv", words.to_s.gsub(' ',''))
